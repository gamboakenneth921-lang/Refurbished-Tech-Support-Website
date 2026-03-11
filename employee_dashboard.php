<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Support Tickets</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg: #f0ede8;
      --surface: #faf9f7;
      --border: #d6d0c8;
      --border-focus: #1a1a1a;
      --text: #1a1a1a;
      --text-muted: #888075;
      --accent: #2c45f5;
      --accent-hover: #1932d4;
      --tag-high-bg: #fff1e6;
      --tag-high: #c44d00;
      --tag-pending-bg: #fffbe6;
      --tag-pending: #9a7500;
      --priority-critical: #d32f2f;
      --priority-high: #e65100;
      --priority-medium: #f57c00;
      --priority-low: #388e3c;
      --radius: 10px;
      --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.05);
    }

    body {
      font-family: 'Syne', sans-serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
      padding: 32px 24px;
    }

    .page-header {
      max-width: 1160px;
      margin: 0 auto 28px;
      display: flex;
      align-items: baseline;
      gap: 12px;
    }

    .page-header h1 {
      font-size: 13px;
      font-weight: 700;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: var(--text-muted);
    }

    .page-header span {
      font-family: 'DM Mono', monospace;
      font-size: 11px;
      color: var(--border);
    }

    .layout {
      max-width: 1160px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 300px;
      gap: 20px;
      align-items: start;
    }

    .card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
    }

    .card-header {
      padding: 24px 28px 0;
    }

    .card-header h2 {
      font-size: 18px;
      font-weight: 800;
      letter-spacing: -0.02em;
    }

    .card-body {
      padding: 24px 28px 28px;
    }

    /* Form */
    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--text-muted);
      margin-bottom: 7px;
    }

    label .req {
      color: var(--accent);
      margin-left: 2px;
    }

    input[type="text"],
    textarea,
    select {
      width: 100%;
      background: var(--bg);
      border: 1.5px solid var(--border);
      border-radius: 7px;
      padding: 11px 14px;
      font-family: 'Syne', sans-serif;
      font-size: 14px;
      color: var(--text);
      transition: border-color 0.18s, box-shadow 0.18s;
      outline: none;
      appearance: none;
      -webkit-appearance: none;
    }

    input[type="text"]::placeholder,
    textarea::placeholder {
      color: #bbb5aa;
    }

    input[type="text"]:focus,
    textarea:focus,
    select:focus {
      border-color: var(--border-focus);
      box-shadow: 0 0 0 3px rgba(26,26,26,0.07);
    }

    textarea {
      resize: vertical;
      min-height: 110px;
      line-height: 1.55;
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    .select-wrapper {
      position: relative;
    }

    .select-wrapper::after {
      content: '';
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      width: 0;
      height: 0;
      border-left: 5px solid transparent;
      border-right: 5px solid transparent;
      border-top: 6px solid var(--text-muted);
      pointer-events: none;
    }

    .btn-submit {
      width: 100%;
      background: var(--accent);
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 14px 20px;
      font-family: 'Syne', sans-serif;
      font-size: 14px;
      font-weight: 700;
      letter-spacing: 0.04em;
      cursor: pointer;
      transition: background 0.18s, transform 0.12s, box-shadow 0.18s;
      margin-top: 4px;
      box-shadow: 0 2px 8px rgba(44,69,245,0.18);
    }

    .btn-submit:hover {
      background: var(--accent-hover);
      box-shadow: 0 4px 16px rgba(44,69,245,0.28);
      transform: translateY(-1px);
    }

    .btn-submit:active {
      transform: translateY(0);
    }

    /* Divider */
    .divider {
      height: 1px;
      background: var(--border);
      margin: 24px 0;
    }

    /* My Tickets section */
    .section-title {
      font-size: 16px;
      font-weight: 800;
      letter-spacing: -0.01em;
      margin-bottom: 16px;
    }

    .ticket-item {
      border: 1.5px solid var(--border);
      border-radius: 8px;
      padding: 16px 18px;
      background: var(--bg);
      transition: border-color 0.15s, box-shadow 0.15s;
      cursor: pointer;
    }

    .ticket-item:hover {
      border-color: #b8b0a6;
      box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }

    .ticket-top {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 7px;
      flex-wrap: wrap;
    }

    .ticket-title {
      font-size: 14px;
      font-weight: 700;
      flex: 1;
    }

    .tag {
      font-family: 'DM Mono', monospace;
      font-size: 10.5px;
      font-weight: 500;
      padding: 3px 9px;
      border-radius: 4px;
      letter-spacing: 0.04em;
    }

    .tag-high {
      background: var(--tag-high-bg);
      color: var(--tag-high);
      border: 1px solid #f5c9a0;
    }

    .tag-pending {
      background: var(--tag-pending-bg);
      color: var(--tag-pending);
      border: 1px solid #f0df90;
    }

    .ticket-desc {
      font-size: 13px;
      color: var(--text-muted);
      margin-bottom: 8px;
      line-height: 1.5;
    }

    .ticket-meta {
      font-family: 'DM Mono', monospace;
      font-size: 10.5px;
      color: #b0a898;
      display: flex;
      gap: 16px;
    }

    /* Sidebar */
    .sidebar {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .user-card .card-body {
      padding: 20px 22px 22px;
    }

    .user-label {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: var(--text-muted);
      margin-bottom: 3px;
    }

    .user-name {
      font-size: 20px;
      font-weight: 800;
      letter-spacing: -0.02em;
      margin-bottom: 18px;
    }

    .stat-row {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
      margin-bottom: 4px;
    }

    .stat {
      background: var(--bg);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 10px 12px;
    }

    .stat-label {
      font-size: 10px;
      font-weight: 700;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--text-muted);
      margin-bottom: 4px;
    }

    .stat-value {
      font-size: 22px;
      font-weight: 800;
      letter-spacing: -0.03em;
    }

    .help-card .card-body {
      padding: 20px 22px 22px;
    }

    .help-title {
      font-size: 15px;
      font-weight: 800;
      margin-bottom: 8px;
    }

    .help-desc {
      font-size: 12.5px;
      color: var(--text-muted);
      line-height: 1.6;
      margin-bottom: 14px;
    }

    .priority-label {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--text-muted);
      margin-bottom: 10px;
    }

    .priority-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .priority-list li {
      font-size: 12px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .priority-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      flex-shrink: 0;
    }

    .dot-critical { background: var(--priority-critical); }
    .dot-high { background: var(--priority-high); }
    .dot-medium { background: var(--priority-medium); }
    .dot-low { background: var(--priority-low); }

    .priority-list li span.p-name {
      font-weight: 700;
    }

    .priority-list li span.p-desc {
      color: var(--text-muted);
    }

    /* Floating help button */
    .fab {
      position: fixed;
      bottom: 28px;
      right: 28px;
      width: 44px;
      height: 44px;
      background: var(--text);
      color: #fff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      font-weight: 700;
      cursor: pointer;
      box-shadow: 0 4px 16px rgba(0,0,0,0.18);
      transition: transform 0.15s, box-shadow 0.15s;
      font-family: 'DM Mono', monospace;
      border: none;
    }

    .fab:hover {
      transform: scale(1.08);
      box-shadow: 0 6px 20px rgba(0,0,0,0.24);
    }

    /* Animations */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(14px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .card { animation: fadeUp 0.4s ease both; }
    .layout > .main-col { animation-delay: 0.05s; }
    .sidebar .card:nth-child(1) { animation-delay: 0.12s; }
    .sidebar .card:nth-child(2) { animation-delay: 0.18s; }

    /* Success toast */
    .toast {
      position: fixed;
      bottom: 80px;
      right: 28px;
      background: #1a1a1a;
      color: #fff;
      padding: 12px 18px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      box-shadow: 0 4px 16px rgba(0,0,0,0.2);
      opacity: 0;
      transform: translateY(10px);
      transition: opacity 0.25s, transform 0.25s;
      pointer-events: none;
    }

    .toast.show {
      opacity: 1;
      transform: translateY(0);
    }

    @media (max-width: 820px) {
      .layout { grid-template-columns: 1fr; }
      .sidebar { order: -1; }
      .stat-row { grid-template-columns: repeat(3, 1fr); }
    }
  </style>
</head>
<body>

  <div class="page-header">
    <h1>Support Portal</h1>
    <span>// helpdesk v2.4</span>
  </div>

  <div class="layout">
    <div class="main-col">
<form method="POST" action="employee_dashboard_process.php">
  <!-- Submit Ticket Card -->
  <div class="card" style="margin-bottom: 20px;">
    <div class="card-header">
      <h2>Submit New Ticket</h2>
    </div>
    <div class="card-body">

      <div class="form-group">
        <label>Email <span class="req">*</span></label>
        <input type="text" id="ticketTitle" name="email" placeholder="Email" required/>
      </div>

      <div class="form-group">
        <label>Ticket Title <span class="req">*</span></label>
        <input type="text" id="ticketTitle" name="title" placeholder="Brief description of the issue" required/>
      </div>

      <div class="form-group">
        <label>Description <span class="req">*</span></label>
        <textarea id="ticketDesc" name="description" placeholder="Detailed description of the problem" required></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Priority</label>
          <div class="select-wrapper">
            <select id="priority" name="priority">
              <option value="Low">Low</option>
              <option value="Medium" selected>Medium</option>
              <option value="High">High</option>
              <option value="Critical">Critical</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>Category</label>
          <div class="select-wrapper">
            <select id="category" name="category">
              <option value="Hardware">Hardware</option>
              <option value="Software">Software</option>
              <option value="Network">Network</option>
              <option value="Account">Account</option>
              <option value="Other">Other</option>
            </select>
          </div>
        </div>
      </div>

      <button type="submit" class="btn-submit">Submit Ticket</button>
    </div>
  </div>
</form>

      <!-- My Tickets Card -->
      <div class="card">
        <div class="card-header">
          <h2>My Tickets</h2>
        </div>
        <div class="card-body" id="ticketList">
          <div class="ticket-item">
            <div class="ticket-top">
              <span class="ticket-title">Login page not loading</span>
              <span class="tag tag-high">High</span>
              <span class="tag tag-pending">Pending</span>
            </div>
            <p class="ticket-desc">Users cannot access the login page, getting 404 error</p>
            <div class="ticket-meta">
              <span>ID: TKT-001</span>
              <span>2026-03-01 09:15 AM</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
      <div class="card user-card">
        <div class="card-body">
          <div class="user-label">User Info</div>
          <div class="user-name">John Smith</div>
          <div class="stat-row">
            <div class="stat">
              <div class="stat-label">Tickets</div>
              <div class="stat-value" id="stat-total">1</div>
            </div>
            <div class="stat">
              <div class="stat-label">Pending</div>
              <div class="stat-value" id="stat-pending">1</div>
            </div>
            <div class="stat">
              <div class="stat-label">Active</div>
              <div class="stat-value" id="stat-active">0</div>
            </div>
          </div>
        </div>
      </div>

      <div class="card help-card">
        <div class="card-body">
          <div class="help-title">Need Help?</div>
          <p class="help-desc">Submit a ticket and our support team will assist you as soon as possible.</p>
          <div class="priority-label">Priority Guidelines</div>
          <ul class="priority-list">
            <li>
              <span class="priority-dot dot-critical"></span>
              <span class="p-name">Critical</span><span class="p-desc">: System down</span>
            </li>
            <li>
              <span class="priority-dot dot-high"></span>
              <span class="p-name">High</span><span class="p-desc">: Major functionality broken</span>
            </li>
            <li>
              <span class="priority-dot dot-medium"></span>
              <span class="p-name">Medium</span><span class="p-desc">: Minor issues</span>
            </li>
            <li>
              <span class="priority-dot dot-low"></span>
              <span class="p-name">Low</span><span class="p-desc">: Questions/requests</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <button class="fab" title="Help">?</button>
  <div class="toast" id="toast">✓ Ticket submitted successfully</div>

  <script>
    let ticketCount = 1;

    function submitTicket() {
      const title = document.getElementById('ticketTitle').value.trim();
      const desc = document.getElementById('ticketDesc').value.trim();
      const priority = document.getElementById('priority').value;
      const category = document.getElementById('category').value;

      if (!title || !desc) {
        shake(document.querySelector('.btn-submit'));
        if (!title) document.getElementById('ticketTitle').focus();
        else document.getElementById('ticketDesc').focus();
        return;
      }

      ticketCount++;
      const id = `TKT-${String(ticketCount).padStart(3, '0')}`;
      const now = new Date();
      const dateStr = now.toISOString().slice(0,10) + ' ' + now.toLocaleTimeString('en-US', {hour:'2-digit', minute:'2-digit'});

      const priorityClass = priority === 'High' || priority === 'Critical' ? 'tag-high' : 'tag-pending';

      const item = document.createElement('div');
      item.className = 'ticket-item';
      item.style.marginTop = '12px';
      item.style.animation = 'fadeUp 0.3s ease both';
      item.innerHTML = `
        <div class="ticket-top">
          <span class="ticket-title">${escapeHtml(title)}</span>
          <span class="tag ${priorityClass}">${priority}</span>
          <span class="tag tag-pending">Pending</span>
        </div>
        <p class="ticket-desc">${escapeHtml(desc)}</p>
        <div class="ticket-meta">
          <span>ID: ${id}</span>
          <span>${dateStr}</span>
          <span>${category}</span>
        </div>
      `;
      document.getElementById('ticketList').appendChild(item);

      // Update stats
      const total = parseInt(document.getElementById('stat-total').textContent) + 1;
      const pending = parseInt(document.getElementById('stat-pending').textContent) + 1;
      document.getElementById('stat-total').textContent = total;
      document.getElementById('stat-pending').textContent = pending;

      // Clear form
      document.getElementById('ticketTitle').value = '';
      document.getElementById('ticketDesc').value = '';
      document.getElementById('priority').selectedIndex = 1;
      document.getElementById('category').selectedIndex = 0;

      showToast();
    }

    function shake(el) {
      el.style.animation = 'none';
      el.offsetHeight;
      el.style.animation = 'shake 0.3s ease';
    }

    function showToast() {
      const t = document.getElementById('toast');
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), 2800);
    }

    function escapeHtml(str) {
      return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }
  </script>

  <style>
    @keyframes shake {
      0%,100% { transform: translateX(0); }
      20% { transform: translateX(-6px); }
      40% { transform: translateX(6px); }
      60% { transform: translateX(-4px); }
      80% { transform: translateX(4px); }
    }
  </style>
</body>
</html>