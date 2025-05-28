<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>Web server is returning an unknown error</title>
  <style>
    *{margin:0;padding:0;box-sizing:border-box}
    body{min-height:100vh;font-family:system-ui,-apple-system,sans-serif;background:#f8f8f8;color:#333}
    .top{background:#222;color:#fff;padding:1rem 2rem;display:flex;align-items:center;justify-content:space-between;font-size:.85rem}
    .top b{color:#f6821f}
    .banner{display:flex;align-items:stretch;max-width:720px;margin:3rem auto;text-align:center}
    .col{flex:1;padding:1.5rem 1rem}
    .col.you{border-right:1px solid #e5e5e5}
    .col.edge{border-right:1px solid #e5e5e5}
    .label{font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;color:#999;margin-bottom:.75rem}
    .node{font-size:.85rem;font-weight:600;color:#222;word-break:break-all}
    .status{display:inline-block;margin-top:.5rem;padding:.15rem .5rem;border-radius:3px;font-size:.7rem;font-weight:600;text-transform:uppercase}
    .ok{background:#e6f9e6;color:#1a7a1a}
    .err{background:#fde8e8;color:#c53030}
    .main{max-width:620px;margin:0 auto;padding:0 2rem}
    h1{font-size:1.5rem;font-weight:700;margin-bottom:.5rem;color:#111}
    h2{font-size:1rem;font-weight:400;color:#666;margin-bottom:2rem}
    .line{width:100%;height:1px;background:#e5e5e5;margin:2rem 0}
    p{font-size:.85rem;line-height:1.8;color:#555}
    .ray{margin-top:2rem;font-size:.75rem;color:#aaa;font-family:ui-monospace,monospace}
  </style>
</head>
<body>
  <div class="top">
    <span id="h"></span>
    <span>Error <b>503</b></span>
  </div>

  <div class="banner">
    <div class="col you">
      <div class="label">You</div>
      <div class="node">Browser</div>
      <span class="status ok">Working</span>
    </div>
    <div class="col edge">
      <div class="label">Edge</div>
      <div class="node" id="h2"></div>
      <span class="status ok">Working</span>
    </div>
    <div class="col host">
      <div class="label">Host</div>
      <div class="node">Server</div>
      <span class="status err">Error</span>
    </div>
  </div>

  <div class="main">
    <h1>Service Temporarily Unavailable</h1>
    <h2>Error 503</h2>

    <div class="line"></div>

    <p><b>What happened?</b><br>
    The web server reported that this service is temporarily unavailable.</p>

    <p style="margin-top:1rem"><b>What can I do?</b><br>
    Please try again in a few minutes. If you are the site owner, check your server configuration and logs for more details.</p>

    <div class="ray" id="ray"></div>
  </div>

  <script>
    var h=location.hostname;
    document.getElementById("h").textContent=h;
    document.getElementById("h2").textContent=h;
    document.getElementById("ray").textContent="Ray ID: "+Math.random().toString(36).slice(2,18)+" | "+new Date().toISOString();
  </script>
</body>
</html>
