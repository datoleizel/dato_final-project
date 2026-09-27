<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once 'database.php';

// Fetch all students from phpcrudleizel
$result = $conn->query("SELECT * FROM students WHERE TRIM(firstname) != '' AND TRIM(lastname) != '' ORDER BY id DESC");
$students = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}
$total_students = count($students);

$first_name = $_SESSION['firstname'] ?? 'Leizel';
$last_name  = $_SESSION['lastname'] ?? 'Dato';
$initials   = strtoupper(substr($first_name, 0, 1) . substr($last_name, 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Roster &bull; Leizel Dato</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,600;0,9..40,700;1,9..40,400&family=Syne:wght@700;800&display=swap" rel="stylesheet">

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
            color: var(--text-heading);
            min-height: 100vh;
            margin: 0;
            padding-bottom: 70px;
        }

        /* Top Header */
        .workspace-navbar {
            background: #ffffff;
            border-bottom: 1.5px solid var(--line-soft);
            padding: 16px 2.5rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand-logo {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.25rem;
            color: var(--text-heading);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .brand-dot {
            width: 12px;
            height: 12px;
            background: var(--amber-warm);
            border-radius: 50%;
            display: inline-block;
        }

        .user-tag {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fafaf9;
            border: 1px solid var(--line-soft);
            padding: 4px 14px 4px 6px;
            border-radius: 30px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: var(--text-heading);
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .btn-signout {
            color: #ef4444;
            background: #fef2f2;
            border: 1px solid #fee2e2;
            padding: 6px 14px;
            font-size: 0.8rem;
            font-weight: 700;
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-signout:hover {
            background: #ef4444;
            color: white;
        }

        /* Workspace Grid */
        .main-shell {
            max-width: 1240px;
            margin: 2.5rem auto;
            padding: 0 1.5rem;
        }

        .editorial-title {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            letter-spacing: -0.02em;
            font-size: 2rem;
            margin-bottom: 4px;
        }

        .kpi-card {
            background: #ffffff;
            border: 1px solid var(--line-soft);
            border-radius: 20px;
            padding: 22px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.02);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .kpi-number {
            font-family: 'Syne', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            margin: 0;
            line-height: 1;
        }

        .kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        /* Main Records Table Card */
        .roster-card {
            background: #ffffff;
            border: 1px solid var(--line-soft);
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.02);
        }

        .table-custom {
            width: 100%;
            border-collapse: collapse;
        }

        .table-custom th {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-body);
            padding: 14px 16px;
            border-bottom: 1.5px solid var(--line-soft);
        }

        .table-custom td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid var(--line-soft);
            font-size: 0.92rem;
        }

        .table-custom tr:hover td {
            background: #fafaf9;
        }

        .btn-brand-action {
            background: var(--text-heading);
            color: #ffffff;
            border: none;
            padding: 8px 18px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.88rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-brand-action:hover {
            background: var(--amber-warm);
            color: #ffffff;
        }

        .btn-icon-action {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid var(--line-soft);
            background: #ffffff;
            color: var(--text-body);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .btn-icon-action:hover {
            background: var(--amber-surface);
            color: var(--amber-warm);
            border-color: #fde68a;
        }

        .btn-icon-action.delete:hover {
            background: #fef2f2;
            color: #ef4444;
            border-color: #fecaca;
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <header class="workspace-navbar d-flex justify-content-between align-items-center">
        <a href="dashboard.php" class="brand-logo">
            <span class="brand-dot"></span>
            <span>Leizel.Roster</span>
        </a>

        <div class="d-flex align-items-center gap-3">
            <div class="user-tag d-none d-sm-flex">
                <div class="user-avatar"><?= $initials; ?></div>
                <div class="small fw-bold pe-2"><?= htmlspecialchars($first_name . ' ' . $last_name); ?></div>
            </div>
            <a href="logout.php" class="btn-signout">Sign Out</a>
        </div>
    </header>

    <main class="main-shell">

        <!-- Top Greeting -->
        <div class="mb-4">
            <h1 class="editorial-title">Student Records</h1>
            <p class="text-secondary small mb-0">Active SQL Schema &bull; <strong class="text-dark">phpcrudleizel</strong></p>
        </div>

        <!-- Feedback Alerts -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success border-0 py-2 px-3 small rounded-3 d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-check-circle-fill"></i>
                <div><?= htmlspecialchars($_GET['success']); ?></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger border-0 py-2 px-3 small rounded-3 d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-exclamation-octagon-fill"></i>
                <div><?= htmlspecialchars($_GET['error']); ?></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- KPI Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4 col-sm-6">
                <div class="kpi-card">
                    <div>
                        <div class="small text-secondary fw-semibold">Total Students</div>
                        <h2 class="kpi-number mt-2" id="studentCounter"><?= $total_students; ?></h2>
                    </div>
                    <div class="kpi-icon" style="background: var(--amber-surface); color: var(--amber-warm);">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="kpi-card">
                    <div>
                        <div class="small text-secondary fw-semibold">Database Node</div>
                        <h4 class="fw-bold mt-2 font-monospace text-dark">phpcrudleizel</h4>
                    </div>
                    <div class="kpi-icon" style="background: #f0fdf4; color: #16a34a;">
                        <i class="bi bi-database-check"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-12">
                <div class="kpi-card">
                    <div>
                        <div class="small text-secondary fw-semibold">Authenticated Profile</div>
                        <h4 class="fw-bold mt-2 text-dark"><?= htmlspecialchars($first_name . ' ' . $last_name); ?></h4>
                    </div>
                    <div class="kpi-icon" style="background: #f5f3ff; color: #7c3aed;">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Roster Table -->
        <div class="roster-card">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <div>
                    <h5 class="fw-bold mb-0">Enrolled Students</h5>
                    <span class="small text-secondary">Live registry entries</span>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <input type="text" id="liveSearchInput" class="form-control form-control-sm rounded-3 px-3" placeholder="Filter by name..." style="width: 200px; border-color: var(--line-soft);">
                    <button class="btn-brand-action" data-bs-toggle="modal" data-bs-target="#modalAddStudent">
                        <i class="bi bi-plus-lg"></i> Add Student
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="studentRecordsBody">
                        <?php if (!empty($students)): ?>
                            <?php foreach ($students as $student): ?>
                                <tr class="student-entry">
                                    <td><span class="font-monospace text-secondary">#LZ-<?= str_pad($student['id'], 3, '0', STR_PAD_LEFT); ?></span></td>
                                    <td class="fw-bold text-dark target-name"><?= htmlspecialchars($student['firstname']); ?></td>
                                    <td class="fw-bold text-dark"><?= htmlspecialchars($student['lastname']); ?></td>
                                    <td><span class="badge rounded-pill bg-light text-success border border-success-subtle px-3 py-1">Enrolled</span></td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <button type="button" class="btn-icon-action" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $student['id']; ?>" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <form action="delete.php" method="POST" onsubmit="return confirm('Permanently remove this student?');" class="m-0">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
                                                <input type="hidden" name="id" value="<?= $student['id']; ?>">
                                                <button type="submit" class="btn-icon-action delete" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="modalEdit<?= $student['id']; ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered text-start">
                                                <div class="modal-content border-0 rounded-4 shadow p-3">
                                                    <form action="update.php" method="POST">
                                                        <div class="modal-header border-0 pb-0">
                                                            <h5 class="modal-title fw-bold">Edit Student Details</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body py-3">
                                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
                                                            <input type="hidden" name="id" value="<?= $student['id']; ?>">

                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold text-secondary">First Name</label>
                                                                <input type="text" name="firstname" class="form-control rounded-3" value="<?= htmlspecialchars($student['firstname']); ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold text-secondary">Last Name</label>
                                                                <input type="text" name="lastname" class="form-control rounded-3" value="<?= htmlspecialchars($student['lastname']); ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn-brand-action">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-secondary">
                                    <i class="bi bi-mortarboard fs-2 d-block mb-1"></i>
                                    No student records found in <code>phpcrudleizel</code>.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Modal: Add Student -->
    <div class="modal fade" id="modalAddStudent" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow p-3">
                <form action="insert.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">

                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Add New Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">First Name</label>
                            <input type="text" name="firstname" class="form-control rounded-3" placeholder="e.g. Maria" maxlength="50" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Last Name</label>
                            <input type="text" name="lastname" class="form-control rounded-3" placeholder="e.g. Santos" maxlength="50" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-brand-action">Save Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const searchInput = document.getElementById('liveSearchInput');
        const rows = document.querySelectorAll('.student-entry');
        const counter = document.getElementById('studentCounter');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase().trim();
                let matchCount = 0;

                rows.forEach(r => {
                    const text = r.querySelector('.target-name').textContent.toLowerCase();
                    if (text.includes(term)) {
                        r.style.display = '';
                        matchCount++;
                    } else {
                        r.style.display = 'none';
                    }
                });

                if (counter) counter.innerText = matchCount;
            });
        }
    </script>
</body>
</html>