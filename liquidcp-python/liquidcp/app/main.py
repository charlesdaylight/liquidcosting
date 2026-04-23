from fastapi import FastAPI

from .schemas import EstimateRequest, EstimateResponse
from .service import estimate_quote

app = FastAPI(title="Liquid CP Engine", version="0.1.0")


@app.get("/health")
def health() -> dict[str, str]:
    return {"status": "ok"}


@app.post("/estimate", response_model=EstimateResponse)
def estimate(request: EstimateRequest) -> EstimateResponse:
    return estimate_quote(request)
