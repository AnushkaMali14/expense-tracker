# 💰 Expense Tracker (PHP & MySQL)

A modern, full-stack Expense Tracker application designed for students and beginners. Built with a focus on clean UI/UX and functional reliability.

## 🔗 Live Demo
**Check it out here:** [https://anushka-expense-app.gamer.gd/](https://anushka-expense-app.gamer.gd/)

## 🚀 Key Features
- **Dashboard Overview**: Instant access to Total Balance, Income, and Expenses.
- **Transaction Management**: Add, Edit, and Delete transactions with ease.
- **Advanced Filtering**: Search by description, filter by category, date range, or transaction type.
- **Visual Analytics**: Interactive Pie and Bar charts powered by Chart.js.
- **Budget Control**: Set a monthly budget and receive automatic warnings if you overspend.
- **Dark Mode**: Stylish dark theme toggle for late-night budgeting.
- **Export Data**: Download your complete transaction history in CSV format.

## 🛠️ Technical Stack
- **Frontend**: HTML5, CSS3 (Glassmorphism), JavaScript (ES6+).
- **Backend**: PHP (PDO for Database consistency and security).
- **Database**: MySQL.
- **Charts**: Chart.js.

## ⚙️ How to Install
1. **Copy Files**: Place the `WDL` folder content into your `htdocs` (XAMPP) or `www` (WAMP) directory.
2. **Database Setup**:
   - Open **phpMyAdmin**.
   - Create a database called `expense_tracker`.
   - Import the `setup.sql` file.
3. **Configure**: Update `config.php` if your MySQL username or password differs from the defaults (root/empty).
4. **Enjoy**: Visit `http://localhost/WDL` in your browser.

## 📂 Folder Structure
- `/css`: Stylesheets.
- `/js`: Frontend logic.
- `config.php`: Database connection.
- `setup.sql`: Initial database structure.
- `index.php`: Login gateway.
