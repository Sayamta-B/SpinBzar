# SpinBzar
SpinBzar is a C2C e-commerce website system built with payment integration (Khalti & eSewa), a complete database-driven backend, and a minimal yet user-friendly UI. It provides both consumer and admin dashboards for smooth commerce operations.

# Key Features:
- Consumer and admin panel for managing e-commerce operations
- Product listing and cart management
- Secure Khalti and eSewa payment integration
- Complete database connectivity (CRUD operations)
- Simple, understandable, and responsive UI design

# Tools Used:
- HTML, CSS, JavaScript, PHP
- MySQL (via XAMPP for local hosting)

This project helped me understand full-stack web development fundamentals, database connectivity, and especially how to integrate payment gateways in real-world systems.

# For Esewa
In the eSewa integration, the payment request must include a signature to verify that the transaction details haven’t been tampered with. For this, eSewa specifies a list of fields that need to be signed — in this case, total_amount, transaction_uuid, and product_code. First, we join these fields in a single string ($raw_string) in the exact order required, like total_amount=100,transaction_uuid=1234,product_code=EPAYTEST. Then, we generate a hash of this string using the hash_hmac function with the SHA256 algorithm and our secret key (provided by eSewa). Finally, this hash is encoded with Base64 to create the signature, which is sent along with the request. On eSewa’s side, they recompute the same signature with their copy of the secret key — if both match, the request is valid; otherwise, the payment is rejected (error like ES104 occurs when it doesn’t match).

# For Khalti
In Khalti’s redirect flow, you don’t generate a signature yourself. Instead, you just send the required parameters such as public_key, product_identity, product_name, amount, and return_url. The user is redirected to Khalti’s page, where they log in and approve the payment. After completion, Khalti sends the user back to your return_url with a pidx (payment index). At that point, you must make a server-to-server verification call to Khalti’s API using your secret key. If Khalti confirms the transaction is Completed, then you can safely grant the service (like issuing a ticket).


# Installation/ Cloning directory
If you are using xampp, 
- Go to the htdocs of xampp in **command prompt** with ```cd C:/xampp/htdocs```
- Write following command to clone the respository
```
git clone https://github.com/Sayamta-B/SpinBzar.git
```
- Start the xampp server and go to the link in the browser: https://localhost/SpinBzar
- Click on **sql** folder
- Click on **createDatabase.php** to create database 'SpinBzar'
- Go back and click on **createTable.php** to create tables
- To use the website, Link/ Url on browser is http://localhost/SpinBzar/program/landing_page.php
