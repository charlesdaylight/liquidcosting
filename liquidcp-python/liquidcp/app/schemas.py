from datetime import datetime

from pydantic import BaseModel, Field, NonNegativeFloat


class EstimateRequest(BaseModel):
    build_profile: str
    distance_new_aerial: NonNegativeFloat
    distance_new_underground: NonNegativeFloat
    distance_existing_aerial: NonNegativeFloat
    distance_existing_duct: NonNegativeFloat
    customer_nrc: NonNegativeFloat
    customer_mrc: NonNegativeFloat
    exchange_rate: NonNegativeFloat
    manual_overrides: dict[str, float | str] = Field(default_factory=dict)


class LineItem(BaseModel):
    code: str
    label: str
    quantity: float
    unit: str
    unit_rate: float
    amount: float


class EstimateResponse(BaseModel):
    rule_set_version: str
    line_items: dict[str, list[LineItem]]
    labour_total: float
    materials_total: float
    admin_total: float
    wayleave_total: float
    equipment_total: float
    subtotal: float
    vat: float
    total_due: float
    build_cost: float
    net_build_cost: float
    roi_months: float | None
    roi_years: float | None
    warnings: list[str]
    engine_version: str
    calculated_at: datetime
