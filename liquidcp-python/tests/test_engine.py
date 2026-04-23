from math import isclose

from fastapi.testclient import TestClient

from liquidcp.app.main import app
from liquidcp.app.schemas import EstimateRequest
from liquidcp.app.service import estimate_quote


def sample_request() -> EstimateRequest:
    return EstimateRequest(
        build_profile="prototype-general",
        distance_new_aerial=1000,
        distance_new_underground=0,
        distance_existing_aerial=0,
        distance_existing_duct=0,
        customer_nrc=800,
        customer_mrc=2765,
        exchange_rate=28,
    )


def test_estimate_matches_visible_workbook_sample():
    result = estimate_quote(sample_request())

    assert result.rule_set_version == "prototype-v1"
    assert isclose(result.labour_total, 13460.0, rel_tol=0, abs_tol=0.01)
    assert isclose(result.materials_total, 98302.9324, rel_tol=0, abs_tol=0.01)
    assert isclose(result.admin_total, 5588.14662, rel_tol=0, abs_tol=0.01)
    assert isclose(result.wayleave_total, 555.0, rel_tol=0, abs_tol=0.01)
    assert isclose(result.equipment_total, 0.0, rel_tol=0, abs_tol=0.01)
    assert isclose(result.subtotal, 117906.07902, rel_tol=0, abs_tol=0.01)
    assert isclose(result.vat, 18864.9726432, rel_tol=0, abs_tol=0.01)
    assert isclose(result.total_due, 136771.0516632, rel_tol=0, abs_tol=0.01)
    assert isclose(result.build_cost, 136771.0516632, rel_tol=0, abs_tol=0.01)
    assert isclose(result.net_build_cost, 135971.0516632, rel_tol=0, abs_tol=0.01)
    assert isclose(result.roi_months, 49.175787219963844, rel_tol=0, abs_tol=0.000001)
    assert isclose(result.roi_years, 4.097982268330321, rel_tol=0, abs_tol=0.000001)


def test_zero_mrc_returns_warning_and_no_roi():
    request = sample_request().model_copy(update={"customer_mrc": 0})

    result = estimate_quote(request)

    assert result.roi_months is None
    assert result.roi_years is None
    assert "customer_mrc" in result.warnings[0].lower()


def test_manual_overrides_adjust_active_ruleset_values():
    request = sample_request().model_copy(
        update={
            "manual_overrides": {
                "rule_set_version": "prototype-v2",
                "vat_rate": 0.2,
                "supervision_rate": 0.1,
            }
        }
    )

    result = estimate_quote(request)

    assert result.rule_set_version == "prototype-v2"
    assert result.admin_total > 5588.14662
    assert result.vat > 18864.9726432


def test_estimate_matches_workbook_formula_pattern_for_underground_and_existing_sections():
    request = sample_request().model_copy(
        update={
            "distance_new_aerial": 0,
            "distance_new_underground": 1200,
            "distance_existing_aerial": 150,
            "distance_existing_duct": 200,
            "customer_nrc": 0,
            "customer_mrc": 1000,
        }
    )

    result = estimate_quote(request)

    assert isclose(result.labour_total, 36510.0, rel_tol=0, abs_tol=0.01)
    assert isclose(result.materials_total, 86574.5128, rel_tol=0, abs_tol=0.01)
    assert isclose(result.admin_total, 6154.22564, rel_tol=0, abs_tol=0.01)
    assert isclose(result.wayleave_total, 6740.0, rel_tol=0, abs_tol=0.01)
    assert isclose(result.total_due, 157735.3365904, rel_tol=0, abs_tol=0.01)

    labour_items = {item.code: item for item in result.line_items["labour"]}
    materials_items = {item.code: item for item in result.line_items["materials"]}
    wayleave_items = {item.code: item for item in result.line_items["wayleave"]}

    assert labour_items["manhole_installation"].quantity == 2
    assert labour_items["install_cable_in_duct"].quantity == 1400
    assert labour_items["aerial_cable_installation"].quantity == 150
    assert labour_items["pole_transportation"].quantity == 1
    assert materials_items["manhole_900mm"].quantity == 2
    assert materials_items["tensioners"].quantity == 2
    assert wayleave_items["trench_fee"].amount == 6000


def test_engine_warns_for_profile_mismatch_zero_route_and_zero_exchange_rate():
    request = sample_request().model_copy(
        update={
            "build_profile": "prototype-mismatch",
            "distance_new_aerial": 0,
            "distance_new_underground": 0,
            "distance_existing_aerial": 0,
            "distance_existing_duct": 0,
            "exchange_rate": 0,
        }
    )

    result = estimate_quote(request)

    warning_text = " ".join(result.warnings).lower()

    assert "build profile" in warning_text
    assert "distance" in warning_text
    assert "exchange rate" in warning_text


def test_health_endpoint_returns_ok():
    client = TestClient(app)

    response = client.get("/health")

    assert response.status_code == 200
    assert response.json() == {"status": "ok"}


def test_estimate_endpoint_returns_contract_fields():
    client = TestClient(app)

    response = client.post("/estimate", json=sample_request().model_dump(mode="json"))

    assert response.status_code == 200
    payload = response.json()
    expected_keys = {
        "rule_set_version",
        "line_items",
        "labour_total",
        "materials_total",
        "admin_total",
        "wayleave_total",
        "equipment_total",
        "subtotal",
        "vat",
        "total_due",
        "build_cost",
        "net_build_cost",
        "roi_months",
        "roi_years",
        "warnings",
        "engine_version",
        "calculated_at",
    }

    assert expected_keys.issubset(payload.keys())


def test_estimate_endpoint_accepts_string_ruleset_version_in_manual_overrides():
    client = TestClient(app)

    payload = sample_request().model_dump(mode="json")
    payload["manual_overrides"] = {
        "rule_set_version": "prototype-v2",
        "vat_rate": 0.2,
    }

    response = client.post("/estimate", json=payload)

    assert response.status_code == 200
    assert response.json()["rule_set_version"] == "prototype-v2"
