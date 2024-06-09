## Summary
Throughout ths project i use several frameworks, although it is just a simple application to leverage my skills. In this project, i implemented:
- A function to integrate with a third party (payment gateway) endpoint. I simply return the response immediately to maintain simplicity.
- A queue to update the customer balance asynchronously. Employing a database isolation level to prevent race conditions.
- The frontend UI enables users to make deposits and withdrawals, view their current balance, and access transaction history.

## Tech stack
- ReactJS + Vite + Typescript
- Laravel
- PostgreSQL
- Docker

## How to run?
Don't worry, I've made a script so you can easily run the application with `docker compose up -d`. The application can be accessed at `http://localhost:5173`
