
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - MNO</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #00796b;
            color: white;
            padding: 20px 0;
            text-align: center;
            font-size: 26px;
            font-weight: bold;
        }
        .intro {
            text-align: center;
            padding: 30px;
            font-size: 18px;
            color: #333;
            max-width: 900px;
            margin: auto;
        }
        .team {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            width: 220px;
            text-align: center;
            overflow: hidden;
        }
        .card img {
            width: 100%;
            height: 280px;
            object-fit: cover;
        }
        .card .name {
            background-color: #00796b;
            color: white;
            padding: 10px;
            font-weight: bold;
        }
        .card .role {
            padding: 10px;
            font-size: 14px;
            color: #444;
        }
    </style>
</head>
<body>

<header>About Us</header>

<div class="intro">
    At MNO, we believe in revolutionizing healthcare with technology. Our application brings doctors, nurses, and medical staff onto a single platform, ensuring seamless patient care, secure communication, and efficient record-keeping – all in one place.
</div>

<div class="team">
    <div class="card">
        <img src="images/malaika.jpg" alt="Malaika Rizwan">
        <div class="name">MALAIKA RIZWAN</div>
        <div class="role">LEADER AND REQUIREMENT ENGINEER</div>
    </div>
    <div class="card">
        <img src="images/noor.jpg" alt="Noor Amir">
        <div class="name">NOOR AMIR</div>
        <div class="role">DEVELOPER AND ARCHITECT</div>
    </div>
    <div class="card">
        <img src="images/oswa.jpg" alt="Oswa Saleem">
        <div class="name">OSWA SALEEM</div>
        <div class="role">TESTER AND MAINTAINER</div>
    </div>
    <div class="card">
        <img src="images/aabish.jpg" alt="Aabish Noor">
        <div class="name">AABISH NOOR</div>
        <div class="role">REQUIREMENT ENGINEER AND MAINTAINER</div>
    </div>
</div>

</body>
</html>
