# 💰 Expense Tracker — PHP & MySQL

A full-stack web application for managing personal income and expenses. The application allows users to track transactions, manage budgets, analyze spending patterns, and export financial data.

## 🔗 Project Links

🚀 **Live Demo:** [https://letstrack.page.gd](https://letstrack.page.gd)

💻 **GitHub Repository:** [https://github.com/AnushkaMali14/expense-tracker](https://github.com/AnushkaMali14/expense-tracker)

---

## 🚀 Key Features

- **Dashboard Overview:** View total balance, income, expenses, and budget information.
- **Transaction Management:** Add, edit, and delete income and expense transactions.
- **Advanced Filtering:** Filter transactions by description, category, date range, and transaction type.
- **Visual Analytics:** Interactive Pie and Bar charts using Chart.js.
- **Budget Control:** Set a monthly budget and monitor spending.
- **Dark Mode:** Toggle between light and dark themes.
- **CSV Export:** Export transaction history as a CSV file.
- **User Authentication:** User registration and login functionality.
- **Responsive UI:** Interface designed to work across different screen sizes.

---

## 🛠️ Technical Stack

### Frontend

- HTML5
- CSS3
- JavaScript (ES6+)
- Chart.js

### Backend

- PHP
- PDO

### Database

- MySQL

### Tools & Deployment

- Git
- GitHub
- phpMyAdmin
- InfinityFree

---

## 🔄 CRUD Operations

The application implements CRUD operations for transaction management:

| Operation | Function |
|---|---|
| **Create** | Add income and expense transactions |
| **Read** | View and filter transactions |
| **Update** | Edit existing transactions |
| **Delete** | Delete transactions |

---

## 📊 Dashboard & Analytics

The dashboard provides an overview of the user's financial activity, including:

- Total income
- Total expenses
- Current balance
- Budget information
- Transaction history
- Spending analysis

Chart.js is used to display financial data through interactive Pie and Bar charts.

---

## 🗄️ Database Structure

The application uses a MySQL relational database with two main tables.

### Users

Stores user account information and budget limits.

Main fields include:

- `id`
- `username`
- `email`
- `password`
- `budget_limit`
- `created_at`

### Transactions

Stores income and expense records associated with users.

Main fields include:

- `id`
- `user_id`
- `amount`
- `type`
- `category`
- `date`
- `description`
- `created_at`

### Relationship

```text
Users (1) ────────────< Transactions (Many)
