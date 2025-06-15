<p align="center">
  <img src="https://user-images.githubusercontent.com/6929121/87441911-486bf600-c611-11ea-9d45-94c215733cf7.png" width="200" alt="RestoMAX Logo">
</p>

<h1 align="center">RestoMAX</h1>

<p align="center">
  <strong>The Digital Brain for Your Modern F&B Business.</strong>
  <br />
  A robust backend designed to orchestrate every aspect of restaurant operations, from table-side orders to profitability reports in your hands.
</p>

<p align="center">
    <img alt="Laravel" src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" />
    <img alt="MySQL" src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white" />
    <img alt="Vue.js" src="https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vue.js&logoColor=4FC08D" />
    <a href="#"><img alt="License: MIT" src="https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge"></a>
</p>

---

**RestoMAX** is more than just a Point of Sale (POS). It is a centralized API platform built with an **API-first** approach using Laravel 11+. It's designed to be the single source of truth consumed by various front-ends, whether it's a cashier application (Vue.js SPA), a Kitchen Display System (KDS), or an analytical dashboard for managers.

## ✨ Why RestoMAX is Different

| Icon | Key Feature | Brief Description |
| :--- | :--- | :--- |
| 🍳 | **Smart Stock Automation** | Ingredient stock is automatically deducted upon every completed order, triggered by a reactive and efficient **Event-Driven** system. |
| 💸 | **Flexible POS API** | Endpoints ready to handle complex order flows, payments, and table management. |
| 👥 | **Staff & Access Control** | Secure every endpoint with role-based middleware (`owner`, `manager`, `cashier`) using **Laravel Sanctum**. |
| 📈 | **Analytics-Ready** | The database structure is designed to easily generate sales reports, product profitability, and Cost of Goods Sold (COGS) analysis. |
| 🍜 | **Dynamic Recipe Management** | Link products with their ingredient recipes through an intuitive many-to-many relationship. |
| 💖 | **CRM & Loyalty Foundation** | Collect customer data and build a loyalty program to increase retention. |

---

## ⚡️ API Endpoint Examples

Here are some of the main available endpoints:

| Method | Endpoint | Description | Required Access Role |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/login` | Log in a user to get an API Token. | Public |
| `GET` | `/api/products` | Get a list of all products. | Authenticated |
| `POST` | `/api/orders` | Create a new order. | `cashier` |
| `POST` | `/api/orders/{order}/complete` | Complete an order & trigger stock deduction. | `cashier` |
| `POST` | `/api/product/{product:slug}/recipes` | Add an ingredient to a product's recipe. | `manager` |
| `GET` | `/api/reports/sales` | Get sales reports. | `manager`, `owner` |

---

## 🛠️ Getting Started (Backend Installation)

1.  **Clone the repository:**
    ```bash
    git clone [https://github.com/username/restomax.git](https://github.com/username/restomax.git)
    cd restomax
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    ```

3.  **Setup file environment:**
    ```bash
    cp .env.example .env
    ```

4.  **Generate application key:**
    ```bash
    php artisan key:generate
    ```

5.  **Configure your database in the `.env` file, then run the migrations:**
    ```bash
    php artisan migrate
    ```

6.  **Run the development server:**
    ```bash
    php artisan serve
    ```
    Your API is now running at `http://localhost:8000`.

---

## 🗺️ Development Roadmap

* [ ] **Payroll Module:** Automate salary calculation based on attendance and incentives.
* [ ] **Online Order Integration:** A gateway to receive orders from GoFood/GrabFood.
* [ ] **Real-time Analytics Dashboard:** Visualize sales and inventory data.
* [ ] **API Documentation (Swagger/OpenAPI):** Create interactive documentation for all endpoints.

---

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

## 👋 Follow me at
<a href="https://github.com/dr5hn/"><img alt="Github @dr5hn" src="https://img.shields.io/static/v1?logo=github&message=Github&color=black&style=flat-square&label=" /></a> <a href="https://twitter.com/dr5hn/"><img alt="Twitter @dr5hn" src="https://img.shields.io/static/v1?logo=twitter&message=Twitter&color=black&style=flat-square&label=" /></a> <a href="https://www.linkedin.com/in/dr5hn/"><img alt="LinkedIn @dr5hn" src="https://img.shields.io/static/v1?logo=linkedin&message=LinkedIn&color=black&style=flat-square&label=&link=https://twitter.com/dr5hn" /></a>
