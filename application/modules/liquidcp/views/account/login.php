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
        :root{--ink:#14233b;--muted:rgba(20,35,59,.67);--brand-a:#d70a7c;--brand-b:#1d9bf0;--brand-deep:#5a1687;--line:rgba(20,35,59,.1)}
        *{box-sizing:border-box}body{margin:0;font-family:"Plus Jakarta Sans","Segoe UI",sans-serif;color:var(--ink);background:radial-gradient(circle at top left,rgba(215,10,124,.16),transparent 26%),radial-gradient(circle at bottom right,rgba(29,155,240,.16),transparent 22%),linear-gradient(180deg,#fbf7fd 0%,#f4f8ff 100%);min-height:100vh}
        .app{min-height:100vh;display:grid;place-items:center;padding:24px}.card{width:min(520px,100%);padding:34px;border-radius:28px;background:#fff;border:1px solid var(--line);box-shadow:0 28px 64px rgba(9,44,51,.12)}
        .brand{display:inline-flex;align-items:center;padding:10px 14px;border-radius:999px;background:linear-gradient(135deg,rgba(215,10,124,.12),rgba(29,155,240,.12));color:var(--brand-deep);font-size:12px;font-weight:800;letter-spacing:.14em;text-transform:uppercase}.support{margin-top:12px;font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--muted)}
        h1{margin:18px 0 10px;font-size:40px;line-height:.96;letter-spacing:-.04em}p{margin:0;color:var(--muted);line-height:1.65}.error{margin-top:18px;padding:14px 16px;border-radius:18px;background:rgba(163,46,46,.07);border:1px solid rgba(163,46,46,.16);color:#8c2d2d}
        label{display:block;margin:20px 0 8px;font-size:12px;font-weight:800;letter-spacing:.09em;text-transform:uppercase;color:rgba(20,35,59,.72)}input{width:100%;height:54px;padding:0 16px;border-radius:14px;border:1px solid rgba(20,35,59,.14);font:inherit}input:focus{outline:none;border-color:rgba(215,10,124,.35);box-shadow:0 0 0 4px rgba(29,155,240,.1)}
        button{width:100%;height:56px;margin-top:24px;border:none;border-radius:14px;background:linear-gradient(135deg,var(--brand-a) 0%,var(--brand-b) 100%);color:#fff;font:inherit;font-weight:800;cursor:pointer}.meta{margin-top:20px;color:var(--muted);text-align:center}.meta a{color:var(--brand-deep);font-weight:800;text-decoration:none}
    </style>
</head>
<body>
<div class="app"><div class="card"><span class="brand">Liquid CP</span><div class="support">By Liquid Intelligent Technologies Zambia</div><h1>Sign in</h1><p>Access your estimate dashboard and continue where you left off.</p>
<?php if ($this->session->flashdata('liquidcp_auth_error')) { ?><div class="error"><?php echo html_escape($this->session->flashdata('liquidcp_auth_error')); ?></div><?php } ?>
<form method="post" action="<?php echo html_escape($action_url); ?>"><input type="hidden" name="<?php echo html_escape($csrf_token_name); ?>" value="<?php echo html_escape($csrf_hash); ?>"><label for="username">Username</label><input id="username" name="username" type="text" autocomplete="username"><label for="password">Password</label><input id="password" name="password" type="password" autocomplete="current-password"><button type="submit">Continue to dashboard</button></form>
<div class="meta">New to Liquid CP? <a href="<?php echo html_escape($signup_url); ?>">Create an account</a></div></div></div>
</body>
</html>
