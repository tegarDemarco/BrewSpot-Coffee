<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><title>403 – Akses Ditolak</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
<style>*{box-sizing:border-box;margin:0;padding:0}body{font-family:'Plus Jakarta Sans',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#f8fafc;text-align:center}
.box{max-width:420px;padding:48px 32px;background:#fff;border-radius:20px;border:1px solid #e2e8f0;box-shadow:0 4px 20px rgba(0,0,0,.08)}
.icon{font-size:3.5rem;margin-bottom:16px}h1{font-size:1.8rem;font-weight:800;color:#0d1b3e;margin-bottom:8px}
p{color:#64748b;margin-bottom:24px;font-size:.9rem;line-height:1.6}.btn{padding:11px 24px;background:#0d1b3e;color:#fff;border-radius:10px;font-weight:700;text-decoration:none;font-size:.85rem;display:inline-block}</style></head>
<body><div class="box"><div class="icon">🚫</div><h1>Akses Ditolak</h1><p>{{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}</p><a href="{{ url()->previous() }}" class="btn">← Kembali</a></div></body></html>
