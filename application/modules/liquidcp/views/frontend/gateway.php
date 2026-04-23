<?php if (! defined('BASEPATH')) { exit('No direct script access allowed'); } ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo html_escape($page_title); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo base_url(); ?>assets/backend/image/rockfm-logo-ico.png" type="image/png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap');
        :root{--ink:#14233b;--muted:rgba(20,35,59,.66);--brand-a:#d70a7c;--brand-b:#1d9bf0;--brand-deep:#5a1687;--line:rgba(20,35,59,.1)}
        *{box-sizing:border-box}
        body{margin:0;font-family:"Plus Jakarta Sans","Segoe UI",sans-serif;color:var(--ink);background:radial-gradient(circle at top left,rgba(215,10,124,.18),transparent 24%),radial-gradient(circle at bottom right,rgba(29,155,240,.18),transparent 24%),linear-gradient(180deg,#fbf7fd 0%,#f4f8ff 58%,#fbfcff 100%)}
        .hero{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:28px 18px}
        .shell{width:min(1220px,100%);display:grid;grid-template-columns:minmax(0,.86fr) minmax(0,1.14fr);gap:34px;align-items:center}
        .content{display:flex;flex-direction:column;align-items:flex-start;gap:18px}
        .brand{display:inline-flex;align-items:center;padding:11px 16px;border-radius:999px;background:linear-gradient(135deg,rgba(215,10,124,.12),rgba(29,155,240,.12));color:var(--brand-deep);font-size:13px;font-weight:800;letter-spacing:.14em;text-transform:uppercase}
        .support{font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--muted)}
        h1{margin:0;font-size:clamp(54px,8vw,92px);line-height:.9;letter-spacing:-.06em;max-width:6ch}
        .actions{display:flex;flex-wrap:wrap;gap:14px;padding-top:6px}
        .btn{display:inline-flex;align-items:center;justify-content:center;min-height:56px;padding:0 24px;border-radius:14px;font-weight:800;text-decoration:none}
        .btn-primary{background:linear-gradient(135deg,var(--brand-a) 0%,var(--brand-b) 100%);color:#fff;box-shadow:0 18px 34px rgba(90,22,135,.22)}
        .btn-secondary{background:#fff;color:var(--ink);border:1px solid var(--line)}
        .visual{position:relative;min-height:700px;padding:26px;border-radius:34px;background:linear-gradient(160deg,#f8eff8 0%,#f7fbff 60%,#eef5ff 100%);border:1px solid rgba(90,22,135,.1);box-shadow:0 34px 80px rgba(20,35,59,.12);overflow:hidden}
        .frame{position:relative;z-index:1;display:grid;grid-template-columns:minmax(0,1fr) 220px;gap:20px;height:100%}
        .device{align-self:center;justify-self:center;width:min(100%,430px);padding:16px;border-radius:36px;background:linear-gradient(180deg,#34154e 0%,#14233b 100%);box-shadow:0 28px 64px rgba(20,35,59,.28)}
        .screen{min-height:560px;padding:18px 18px 22px;border-radius:28px;background:linear-gradient(180deg,#fbfffe 0%,#edf6f6 100%)}
        .notch{width:92px;height:18px;margin:0 auto 18px;border-radius:999px;background:#153a40}
        .screen-row{display:flex;justify-content:space-between;gap:12px;align-items:center;margin-bottom:16px}
        .pill{display:inline-flex;align-items:center;padding:10px 14px;border-radius:999px;background:linear-gradient(135deg,rgba(215,10,124,.12),rgba(29,155,240,.12));color:var(--brand-deep);font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}
        .pill.soft{background:rgba(8,42,49,.08);color:#3b5b62}
        .hero-card{padding:20px;border-radius:24px;background:linear-gradient(135deg,var(--brand-a) 0%,var(--brand-b) 100%);color:#fff}
        .hero-card span{display:block;font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.74);margin-bottom:10px}
        .hero-card strong{display:block;font-size:36px;line-height:1}
        .hero-card small{display:block;margin-top:12px;font-size:14px;color:rgba(255,255,255,.78)}
        .stack{display:grid;gap:14px;margin-top:16px}
        .metric{padding:16px 18px;border-radius:20px;background:#fff;border:1px solid rgba(90,22,135,.08);box-shadow:0 12px 26px rgba(20,35,59,.06)}
        .metric-row{display:flex;justify-content:space-between;gap:10px;margin-bottom:12px;color:var(--muted);font-size:14px}
        .bar{height:10px;border-radius:999px;background:rgba(20,35,59,.08);overflow:hidden}.bar span{display:block;height:100%;border-radius:999px;background:linear-gradient(90deg,var(--brand-a) 0%,var(--brand-b) 100%)}
        .side-cards{display:flex;flex-direction:column;justify-content:center;gap:16px}
        .note{padding:18px;border-radius:22px;background:rgba(255,255,255,.82);border:1px solid rgba(90,22,135,.1)}
        .note strong{display:block;font-size:17px}.note span{display:block;margin-top:8px;font-size:13px;color:var(--muted)}
        .chips{position:absolute;left:24px;right:24px;bottom:24px;z-index:2;display:flex;gap:10px;flex-wrap:wrap}
        .chip{display:inline-flex;align-items:center;padding:10px 14px;border-radius:999px;background:#fff;border:1px solid rgba(90,22,135,.08);box-shadow:0 14px 28px rgba(20,35,59,.08);font-size:14px;font-weight:700}
        @media (max-width:1040px){.shell{grid-template-columns:1fr}.content{order:-1}h1{max-width:8ch}}
        @media (max-width:720px){.visual{min-height:auto;padding:18px 18px 90px}.frame{grid-template-columns:1fr}.side-cards{display:none}.device{width:100%}.screen{min-height:500px}.actions{width:100%}.btn{width:100%}}
    </style>
</head>
<body>
<section class="hero">
    <div class="shell">
        <div class="content">
            <span class="brand">Liquid CP</span>
            <div class="support">By Liquid Intelligent Technologies Zambia</div>
            <h1>Build fibre estimates faster.</h1>
            <div class="actions">
                <a class="btn btn-primary" href="<?php echo html_escape($login_url); ?>">Sign in</a>
                <a class="btn btn-secondary" href="<?php echo html_escape($signup_url); ?>">Create account</a>
            </div>
        </div>
        <div class="visual" aria-hidden="true">
            <div class="frame">
                <div class="device"><div class="screen"><div class="notch"></div><div class="screen-row"><span class="pill">Estimate studio</span><span class="pill soft">Step 2 of 4</span></div><div class="hero-card"><span>Projected total</span><strong>ZMW 136,771</strong><small>LCP-20260422-0005</small></div><div class="stack"><div class="metric"><div class="metric-row"><span>New aerial</span><strong>1,000 m</strong></div><div class="bar"><span style="width:82%"></span></div></div><div class="metric"><div class="metric-row"><span>Net build cost</span><strong>ZMW 135,971</strong></div><div class="bar"><span style="width:74%"></span></div></div><div class="metric"><div class="metric-row"><span>ROI</span><strong>4.1 years</strong></div><div class="bar"><span style="width:61%"></span></div></div></div></div></div>
                <div class="side-cards"><div class="note"><strong>Saved estimate flow</strong><span>Studio, review, save, return.</span></div><div class="note"><strong>Shareable outputs</strong><span>Public quote links and PDFs after save.</span></div></div>
            </div>
            <div class="chips"><span class="chip">Private dashboard</span><span class="chip">Saved PDFs</span><span class="chip">Shareable quotes</span></div>
        </div>
    </div>
</section>
</body>
</html>
