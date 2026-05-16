# AI Reflection - Richard

## Project

TokenRedemption is my capstone project. It is a Laravel-based reward redemption application that allows employees to view available rewards, redeem products using tokens, and check the status of their orders. The employee-facing side of the application uses React with Inertia.js, while the admin panel uses Laravel Blade views. Admin users can manage products, users, token balances, and customer orders.

## How I Used AI

I used AI as a development support tool throughout parts of the project. My main use of AI was to help review code structure, improve documentation, and troubleshoot issues while I continued making the final decisions about the application. For example, AI helped me understand how the React/Inertia pages connected to Laravel routes and how data could be passed from the backend to the frontend.

AI was also helpful when updating the README file so that the setup instructions matched the current version of the project. This included documenting the database import process using the SQL dump and explaining which parts of the project use React, Inertia, Laravel, and Blade.

## What AI Helped With

- Updating the README to match the current Laravel, React, and Inertia setup.
- Documenting the SQL dump import process using `database/TokenRedemption.sql`.
- Wiring Laravel routes to return authenticated user order data.
- Improving the public React layout and dashboard navigation.
- Creating clearer explanations for deployment and shared hosting issues.
- Reviewing where new frontend features should be placed in the project structure.

## What I Learned

This project helped me better understand how a Laravel application can work with a React frontend through Inertia.js. I learned that Laravel is still responsible for routing, authentication, database access, and returning data, while React is responsible for rendering the user interface. This helped me see how full-stack applications can be organized without needing to build a separate API for every page.

I also learned more about deployment. A feature can work correctly on localhost but fail on shared hosting if the correct route files, React components, compiled Vite assets, or cache-clearing steps are missed. This helped me understand the importance of keeping the local and production environments in sync.

## Challenges

One challenge was deciding where different features should belong. For example, the employee order-status page needed to be a React frontend page, but it still required Laravel backend logic to securely load only the orders that belonged to the authenticated user. This helped me think more carefully about separating frontend display logic from backend data and security logic.

Another challenge was maintaining two different frontend approaches in the same project. The employee side uses React and Inertia, while the admin panel uses Blade. This required me to pay attention to which files controlled each part of the application.

## Final Thoughts

Using AI helped me move through some development tasks more efficiently, especially when reviewing files, improving documentation, and troubleshooting deployment problems. However, AI did not replace the need to understand the code. I still had to test the application, decide which changes made sense, and make sure the final project worked for the intended users.

Overall, AI was most useful as a support tool. It helped me organize my work, identify possible issues, and improve the quality of the project, while the learning came from applying those suggestions and understanding how the application worked as a full system.
