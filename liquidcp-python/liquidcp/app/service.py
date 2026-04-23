from __future__ import annotations

from datetime import datetime, UTC
from dataclasses import dataclass, replace
from decimal import Decimal, ROUND_DOWN, ROUND_HALF_UP, ROUND_UP

from .rules import PROTOTYPE_RULESET, PrototypeRuleSet
from .schemas import EstimateRequest, EstimateResponse, LineItem

ENGINE_VERSION = "liquidcp-engine-0.2.0"
ZERO = Decimal("0")


@dataclass(frozen=True)
class WorkbookQuantities:
    trenching_qty: Decimal
    laying_duct_qty: Decimal
    install_cable_in_duct_qty: Decimal
    manhole_qty: Decimal
    joint_closure_qty: Decimal
    aerial_install_qty: Decimal
    poles_qty: Decimal
    patch_panel_qty: Decimal
    closure_install_qty: Decimal
    stay_wire_qty: Decimal
    cable_frame_qty: Decimal
    survey_qty: Decimal
    site_prep_qty: Decimal
    splicing_qty: Decimal
    pole_transport_qty: Decimal
    transport_qty: Decimal
    total_cable_qty: Decimal
    tensioners_qty: Decimal
    pole_clamps_qty: Decimal


def _to_decimal(value: float | int | str | Decimal) -> Decimal:
    if isinstance(value, Decimal):
        return value
    return Decimal(str(value))


def _excel_round(value: float | Decimal) -> Decimal:
    return _to_decimal(value).quantize(Decimal("1"), rounding=ROUND_HALF_UP)


def _excel_roundup(value: float | Decimal) -> Decimal:
    return _to_decimal(value).quantize(Decimal("1"), rounding=ROUND_UP)


def _excel_rounddown(value: float | Decimal) -> Decimal:
    return _to_decimal(value).quantize(Decimal("1"), rounding=ROUND_DOWN)


def _truncate(value: float | Decimal) -> Decimal:
    return _to_decimal(value).to_integral_value(rounding=ROUND_DOWN)


def _line_item(code: str, label: str, quantity: float | Decimal, unit: str, unit_rate: float | Decimal) -> LineItem:
    quantity_decimal = _to_decimal(quantity)
    unit_rate_decimal = _to_decimal(unit_rate)
    amount = quantity_decimal * unit_rate_decimal
    return LineItem(
        code=code,
        label=label,
        quantity=float(quantity_decimal),
        unit=unit,
        unit_rate=float(unit_rate_decimal),
        amount=float(amount),
    )


def _sum_amounts(items: list[LineItem]) -> Decimal:
    return sum((_to_decimal(item.amount) for item in items), ZERO)


def _apply_manual_overrides(
    ruleset: PrototypeRuleSet,
    overrides: dict[str, float | str],
) -> PrototypeRuleSet:
    if not overrides:
        return ruleset

    material_multipliers = dict(ruleset.material_exchange_multipliers)
    for key in list(material_multipliers.keys()):
        if key in overrides:
            material_multipliers[key] = float(overrides[key])

    return replace(
        ruleset,
        version=str(overrides.get("rule_set_version", ruleset.version)),
        vat_rate=float(overrides.get("vat_rate", ruleset.vat_rate)),
        supervision_rate=float(overrides.get("supervision_rate", ruleset.supervision_rate)),
        thrust_boring_fee_rate=float(overrides.get("thrust_boring_fee_rate", ruleset.thrust_boring_fee_rate)),
        cisco_4221_exchange_multiplier=float(
            overrides.get("cisco_4221_exchange_multiplier", ruleset.cisco_4221_exchange_multiplier)
        ),
        tplink_archer_c6_exchange_multiplier=float(
            overrides.get("tplink_archer_c6_exchange_multiplier", ruleset.tplink_archer_c6_exchange_multiplier)
        ),
        material_exchange_multipliers=material_multipliers,
    )


def _derive_quantities(request: EstimateRequest) -> WorkbookQuantities:
    new_aerial = _to_decimal(request.distance_new_aerial)
    new_underground = _to_decimal(request.distance_new_underground)
    existing_aerial = _to_decimal(request.distance_existing_aerial)
    existing_duct = _to_decimal(request.distance_existing_duct)

    trenching_qty = new_underground
    laying_duct_qty = new_underground
    install_cable_in_duct_qty = new_underground + existing_duct

    manhole_basis = ZERO
    if new_underground > Decimal("999"):
        manhole_basis = Decimal("1") if trenching_qty < Decimal("1001") else (trenching_qty / Decimal("1000")) + Decimal("1")

    manhole_qty = _truncate(manhole_basis)
    joint_closure_qty = _excel_rounddown(new_underground / Decimal("4000"))
    aerial_install_qty = new_aerial + existing_aerial
    poles_qty = _excel_round(new_aerial / Decimal("95"))
    patch_panel_qty = Decimal("1")
    closure_install_qty = _excel_rounddown(new_aerial / Decimal("4000"))
    stay_wire_qty = _excel_round(new_aerial / Decimal("2000"))
    cable_frame_qty = _excel_rounddown(new_aerial / Decimal("2000"))
    survey_qty = Decimal("1")
    site_prep_qty = Decimal("1")
    splicing_qty = Decimal("4")
    pole_transport_qty = _excel_roundup(aerial_install_qty / Decimal("1800")) if aerial_install_qty > ZERO else ZERO
    transport_qty = Decimal("1")
    total_cable_qty = new_underground + existing_aerial + existing_duct + new_aerial
    tensioners_qty = _excel_roundup((aerial_install_qty / Decimal("255")) * Decimal("2")) if aerial_install_qty > ZERO else ZERO
    pole_clamps_qty = poles_qty + stay_wire_qty + (tensioners_qty / Decimal("2"))

    return WorkbookQuantities(
        trenching_qty=trenching_qty,
        laying_duct_qty=laying_duct_qty,
        install_cable_in_duct_qty=install_cable_in_duct_qty,
        manhole_qty=manhole_qty,
        joint_closure_qty=joint_closure_qty,
        aerial_install_qty=aerial_install_qty,
        poles_qty=poles_qty,
        patch_panel_qty=patch_panel_qty,
        closure_install_qty=closure_install_qty,
        stay_wire_qty=stay_wire_qty,
        cable_frame_qty=cable_frame_qty,
        survey_qty=survey_qty,
        site_prep_qty=site_prep_qty,
        splicing_qty=splicing_qty,
        pole_transport_qty=pole_transport_qty,
        transport_qty=transport_qty,
        total_cable_qty=total_cable_qty,
        tensioners_qty=tensioners_qty,
        pole_clamps_qty=pole_clamps_qty,
    )


def _append_input_warnings(
    warnings: list[str],
    request: EstimateRequest,
    ruleset: PrototypeRuleSet,
    quantities: WorkbookQuantities,
) -> None:
    if request.build_profile != ruleset.code:
        warnings.append(
            f"Build profile '{request.build_profile}' does not match active ruleset '{ruleset.code}'; workbook defaults were used."
        )

    total_route_distance = (
        _to_decimal(request.distance_new_aerial)
        + _to_decimal(request.distance_new_underground)
        + _to_decimal(request.distance_existing_aerial)
        + _to_decimal(request.distance_existing_duct)
    )
    if total_route_distance <= ZERO:
        warnings.append("No route distance was provided; the estimate contains only fixed and default workbook costs.")

    if _to_decimal(request.exchange_rate) <= ZERO:
        warnings.append("Exchange rate is zero, so imported material and equipment rates were priced at zero.")

    if quantities.poles_qty <= ZERO and quantities.aerial_install_qty > ZERO:
        warnings.append("Aerial distance exists without new-pole demand; the workbook formula assumes existing poles for that section.")


def estimate_quote(request: EstimateRequest, ruleset: PrototypeRuleSet = PROTOTYPE_RULESET) -> EstimateResponse:
    ruleset = _apply_manual_overrides(ruleset, request.manual_overrides)
    warnings: list[str] = []
    quantities = _derive_quantities(request)
    exchange_rate = _to_decimal(request.exchange_rate)
    _append_input_warnings(warnings, request, ruleset, quantities)

    labour_items = [
        _line_item("trenching_normal_soil", "Trenching in normal soil", quantities.trenching_qty, "m", 18.0),
        _line_item("laying_duct_warning_tape", "Laying of duct and warning tape", quantities.laying_duct_qty, "m", 4.5),
        _line_item("install_cable_in_duct", "Installation of cable in duct", quantities.install_cable_in_duct_qty, "m", 3.5),
        _line_item("manhole_installation", "Manhole installation", quantities.manhole_qty, "Each", 320.0),
        _line_item("joint_closure_installation", "Installation of joint closure", quantities.joint_closure_qty, "Each", 100.0),
        _line_item("aerial_cable_installation", "Installation of aerial cable", quantities.aerial_install_qty, "m", 8.0),
        _line_item("pole_installation", "Installation of poles", quantities.poles_qty, "each", 220.0),
        _line_item("patch_panel_installation", "Installation of Patch Panel / TB", quantities.patch_panel_qty, "each", 40.0),
        _line_item("closure_installation", "Installation of closure", quantities.closure_install_qty, "each", 100.0),
        _line_item("stay_wire_installation", "Installation to stay wire", quantities.stay_wire_qty, "each", 270.0),
        _line_item("cable_frame_installation", "Installation of cable frame", quantities.cable_frame_qty, "each", 100.0),
        _line_item("survey_utilities", "Survey with utilities", quantities.survey_qty, "each", 150.0),
        _line_item("site_preparation", "Site preparation", quantities.site_prep_qty, "each", 400.0),
        _line_item("splicing_testing", "Splicing and testing", quantities.splicing_qty, "each", 45.0),
        _line_item("pole_transportation", "Pole transportation", quantities.pole_transport_qty, "each", 1500.0),
        _line_item("transport", "Transport", quantities.transport_qty, "each", 500.0),
    ]
    labour_total = _sum_amounts(labour_items)

    multipliers = ruleset.material_exchange_multipliers
    materials_items = [
        _line_item("media_converter", "Media Convertor", 1.0, "Pcs", _to_decimal(multipliers["media_converter"]) * exchange_rate),
        _line_item("sfp_10km", "SFP 10km", 2.0, "Pcs", _to_decimal(multipliers["sfp_10km"]) * exchange_rate),
        _line_item("patch_code", "Patch code", 2.0, "Pcs", _to_decimal(multipliers["patch_code"]) * exchange_rate),
        _line_item("cable_12_core", "Cable 12 Core", quantities.total_cable_qty, "m", 30.0),
        _line_item("manhole_900mm", "Man hole 900mm", quantities.manhole_qty, "Each", 6300.0),
        _line_item("warning_tape", "Warning tape", quantities.trenching_qty, "m", _to_decimal(multipliers["warning_tape"]) * exchange_rate),
        _line_item("hdpe_pipe", "32mm HDPE Pipe PE100 Pipe Class 6", quantities.trenching_qty, "m", _to_decimal(multipliers["hdpe_pipe"]) * exchange_rate),
        _line_item("downlead_clamp", "Downlead clamp", quantities.cable_frame_qty * Decimal("2"), "Each", 240.0),
        _line_item("inline_joint_closure", "Inline joint closure", quantities.joint_closure_qty, "Pcs", _to_decimal(multipliers["inline_joint_closure"]) * exchange_rate),
        _line_item("patch_panel_splice_box", "Patch Panel / Splice box", 1.0, "Each", _to_decimal(multipliers["patch_panel_splice_box"]) * exchange_rate),
        _line_item("joint_closure_dome", "Joint closure (Dome)", quantities.closure_install_qty, "Pcs", _to_decimal(multipliers["joint_closure_dome"]) * exchange_rate),
        _line_item("cable_frame", "Cable Frame", quantities.cable_frame_qty, "Each", _to_decimal(multipliers["cable_frame"]) * exchange_rate),
        _line_item("wooden_poles", "Wooden Poles", quantities.poles_qty, "Each", 4500.0),
        _line_item("pole_clamps", "Pole clamps", quantities.pole_clamps_qty, "Each", _to_decimal(multipliers["pole_clamps"]) * exchange_rate),
        _line_item("tensioners", "Tensioners", quantities.tensioners_qty, "Each", _to_decimal(multipliers["tensioners"]) * exchange_rate),
        _line_item("suspension_clamps", "Suspension clamps", quantities.tensioners_qty, "Each", _to_decimal(multipliers["suspension_clamps"]) * exchange_rate),
        _line_item("stay_wire_assembly", "Stay wire and assembly complete", quantities.stay_wire_qty, "Pcs", _to_decimal(multipliers["stay_wire_assembly"]) * exchange_rate),
    ]
    materials_total = _sum_amounts(materials_items)

    admin_items = [
        _line_item("supervision", "Supervision", 1.0, "all", _to_decimal(ruleset.supervision_rate) * (materials_total + labour_total))
    ]
    admin_total = _sum_amounts(admin_items)

    thrust_boring_qty = float(request.manual_overrides.get("thrust_boring_qty", 0.0))
    equipment_cisco_qty = float(request.manual_overrides.get("equipment_cisco_4221_qty", 0.0))
    equipment_tplink_qty = float(request.manual_overrides.get("equipment_tplink_archer_c6_qty", 0.0))

    wayleave_items = [
        _line_item("application_fee", "Application Fee", 1.0, "Each", 500.0),
        _line_item("trench_fee", "Trench fee", quantities.trenching_qty, "Each", 5.0),
        _line_item("thrustboring_fee", "Thrustboring Fee", thrust_boring_qty, "m", ruleset.thrust_boring_fee_rate),
        _line_item("pole_levy", "Pole levy", quantities.poles_qty, "Each", 5.0),
        _line_item("manhole_levy", "Manhole levy", quantities.manhole_qty, "each", 120.0),
    ]
    wayleave_total = _sum_amounts(wayleave_items)

    equipment_items = [
        _line_item("cisco_4221", "Cisco 4221", equipment_cisco_qty, "Each", _to_decimal(ruleset.cisco_4221_exchange_multiplier) * exchange_rate),
        _line_item("tplink_archer_c6", "Tplink Archer C6", equipment_tplink_qty, "Each", _to_decimal(ruleset.tplink_archer_c6_exchange_multiplier) * exchange_rate),
    ]
    equipment_total = _sum_amounts(equipment_items)

    subtotal = labour_total + materials_total + admin_total + wayleave_total + equipment_total
    vat = subtotal * _to_decimal(ruleset.vat_rate)
    total_due = subtotal + vat
    build_cost = total_due
    net_build_cost = build_cost - _to_decimal(request.customer_nrc)

    roi_months = None
    roi_years = None
    if request.customer_mrc > 0:
        roi_months_decimal = net_build_cost / _to_decimal(request.customer_mrc)
        roi_years_decimal = roi_months_decimal / Decimal("12")
        roi_months = float(roi_months_decimal)
        roi_years = float(roi_years_decimal)
    else:
        warnings.append("customer_mrc is zero, ROI cannot be calculated.")

    if net_build_cost <= ZERO:
        warnings.append("Customer NRC fully offsets the build cost, so the net build cost is zero or negative.")

    return EstimateResponse(
        rule_set_version=ruleset.version,
        line_items={
            "labour": labour_items,
            "materials": materials_items,
            "admin": admin_items,
            "wayleave": wayleave_items,
            "equipment": equipment_items,
        },
        labour_total=float(labour_total),
        materials_total=float(materials_total),
        admin_total=float(admin_total),
        wayleave_total=float(wayleave_total),
        equipment_total=float(equipment_total),
        subtotal=float(subtotal),
        vat=float(vat),
        total_due=float(total_due),
        build_cost=float(build_cost),
        net_build_cost=float(net_build_cost),
        roi_months=roi_months,
        roi_years=roi_years,
        warnings=warnings,
        engine_version=ENGINE_VERSION,
        calculated_at=datetime.now(UTC),
    )
