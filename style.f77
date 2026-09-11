/* General Page Styling */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f7f6;
    margin: 0;
    padding: 0;
}

/* Navigation Bar */
.navbar {
    background-color: #2c3e50;
    padding: 15px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.navbar a { color: white; text-decoration: none; margin-left: 15px; }

/* Hero Section */
.hero {
    background-color: #3498db;
    color: white;
    text-align: center;
    padding: 50px 20px;
    margin-bottom: 20px;
}

/* Job Cards Container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

/* Individual Job Card */
.job-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    width: 300px;
    padding: 20px;
    transition: transform 0.2s;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.job-card:hover { transform: translateY(-5px); }
.job-title { font-size: 18px; font-weight: bold; color: #2c3e50; }
.company-name { color: #7f8c8d; font-size: 14px; margin-bottom: 10px; }
.salary { color: #27ae60; font-weight: bold; margin: 10px 0; }
.btn-details {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
}

/* Modal (Popup) Styling */
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background-color: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
}
.modal-content {
    background-color: white;
    padding: 30px;
    border-radius: 8px;
    width: 500px;
    position: relative;
}
.close-btn {
    position: absolute;
    top: 10px; right: 15px;
    font-size: 24px;
    cursor: pointer;
}
.btn-apply {
    display: inline-block;
    background-color: #e74c3c;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 15px;
}