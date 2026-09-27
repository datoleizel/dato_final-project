<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leizel Academic Portal &bull; Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,600;0,9..40,800;1,9..40,400&family=Syne:wght@700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --amber-warm: #d97706;
            --amber-hover: #b45309;
            --amber-surface: #fffbeb;
            --surface-bg: #fcfbf9;
            --card-box: #ffffff;
            --text-heading: #1c1917;
            --text-body: #78716c;
            --line-soft: #e7e5e4;
        }

        * {
            box-sizing: border-box;
            font-family: 'DM Sans', sans-serif;
        }

        body {
            background-color: var(--surface-bg);
            background-image: radial-gradient(#d6d3d1 0.75px, transparent 0.75px);
            background-size: 20px 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .auth-container {
            width: 100%;
            max-width: 440px;
            background: var(--card-box);
            border: 1px solid var(--line-soft);
            border-radius: 28px;
            padding: 44px 36px;
            box-shadow: 0 12px 30px rgba(28, 25, 23, 0.05);
            position: relative;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--amber-surface);
            color: var(--amber-warm);
            padding: 6px 14px;
            border-radius: 40px;
            font-size: 0.78rem;
            font-weight: 700;
            border: 1px solid #fde68a;
            margin-bottom: 24px;
        }

        .auth-title {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            color: var(--text-heading);
            font-size: 1.85rem;
            letter-spacing: -0.03em;
            margin-bottom: 4px;
        }

        .custom-input {
            background: #fafaf9;
            border: 1.5px solid var(--line-soft);
            border-radius: 12px;
            padding: 13px 16px;
            font-size: 0.92rem;
            color: var(--text-heading);
            transition: all 0.2s;
        }

        .custom-input:focus {
            background: #ffffff;
            border-color: var(--amber-warm);
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.12);
            outline: none;
        }

        .btn-auth-submit {
            background: var(--text-heading);
            color: #ffffff;
            font-weight: 700;
            padding: 13px;
            border-radius: 12px;
            border: none;
            width: 100%;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-auth-submit:hover {
            background: var(--amber-warm);
            color: #ffffff;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <div class="text-center">
            <div class="brand-badge">
                <i class="bi bi-layers-fill"></i> phpcrudleizel
            </div>
            <h1 class="auth-title">Welcome back.</h1>
            <p class="text-secondary small mb-4">Please log in to manage your student records.</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger border-0 small py-2 px-3 mb-3 rounded-3 d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-circle-fill"></i>
                <div><?= htmlspecialchars($_GET['error']); ?></div>
            </div>
        <?php endif; ?>

        <form action="pakicheck.php" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold text-dark">Username</label>
                <input type="text" name="username" class="form-control custom-input" placeholder="leizel" required autofocus>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-dark">Password</label>
                <input type="password" name="password" class="form-control custom-input" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-auth-submit">
                <span>Sign in to Dashboard</span>
                <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <div class="text-center mt-4 pt-2">
            <span class="text-muted small">&copy; <?= date('Y'); ?> Leizel Dato &bull; System Workspace</span>
        </div>
    </div>

</body>
</html>