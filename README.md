# EWEB Dynamic Mailer

[![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-8.1%2B-8892bf.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-GPLv2%2B-orange.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

High-performance Mass Mailing System for WordPress. Optimized for flexibility with custom CSV support and built-in **Open Tracking**. Part of the **EWEB Plugin Suite**.

## 🚀 Key Features

- **Dynamic CSV Processing:** Upload and process flexible subscriber lists.
- **Open Tracking:** Transparent pixel-based system to track email engagement.
- **Deduplication:** Automatic logic to prevent sending multiple emails to the same address.
- **Persistent History:** Database-driven logs of every campaign and delivery status.
- **Admin UI:** Clean dashboard for managing campaigns and viewing performance history.

## 🏗️ Technical Architecture

- **`db-handler.php`**: Manages custom database tables for high-performance tracking.
- **`tracker.php`**: Handles asynchronous tracking requests without affecting site speed.
- **`sender.php`**: Optimized mailing engine using WordPress native wrappers.
- **`history.php`**: Reporting logic for campaign analysis.

## 🛠️ Installation

1. Upload the `mc-dynamic-mailer` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Access the **Mars Mailer** menu to start your first campaign.

## 🔄 Updates

Supports automatic updates directly from GitHub. Repository slug: `Yisus-Develop/eweb-dynamic-mailer`.

---
Developed by **Yisus Develop**
[enlaweb.co](https://enlaweb.co/)
