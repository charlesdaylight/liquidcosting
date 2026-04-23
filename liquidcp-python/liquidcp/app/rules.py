from dataclasses import dataclass, field


@dataclass(frozen=True)
class PrototypeRuleSet:
    code: str = "prototype-general"
    version: str = "prototype-v1"
    vat_rate: float = 0.16
    supervision_rate: float = 0.05
    thrust_boring_fee_rate: float = 100.0
    cisco_4221_exchange_multiplier: float = 580.0
    tplink_archer_c6_exchange_multiplier: float = 55.0
    material_exchange_multipliers: dict[str, float] = field(
        default_factory=lambda: {
            "media_converter": 66.5,
            "sfp_10km": 12.49,
            "patch_code": 2.2019,
            "warning_tape": 0.0725,
            "hdpe_pipe": 0.581,
            "inline_joint_closure": 32.9,
            "patch_panel_splice_box": 12.7699,
            "joint_closure_dome": 34.3015,
            "cable_frame": 21.22,
            "pole_clamps": 11.2445,
            "tensioners": 17.2204,
            "suspension_clamps": 21.3468,
            "stay_wire_assembly": 74.43,
        }
    )


PROTOTYPE_RULESET = PrototypeRuleSet()

