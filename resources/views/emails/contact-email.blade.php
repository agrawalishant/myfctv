<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 1s ease-out;
        }

        h1 {
            color: #333333;
            text-align: center;
        }

        p {
            color: #555555;
            margin-bottom: 15px;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333333;
        }

        input,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            background-color: #007BFF;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            display: block;
            margin: 20px auto 0;
        }

        .footer {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            text-align: center;
            color: #777777;
        }

        .animated {
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="container animated">
        <h1>Contact Form Submission</h1>
        <p>
            You have received a new contact form submission. Here are the details:
        </p>

        <p><strong>First Name:</strong> {{ $data['first_name'] }}</p>
        <p><strong>Last Name:</strong> {{ $data['last_name'] }}</p>
        <p><strong>Phone:</strong> {{ $data['phone'] }}</p>
        <p><strong>Email:</strong> {{ $data['email'] }}</p>
        <p><strong>Message:</strong> {{ $data['message'] }}</p>


        <p class="footer">
            Best regards,<br>
            Your Company<br>
            Address, City, Country<br>
            Phone: +123 456 789<br>
            Email: info@yourcompany.com
        </p>
    </div>
</body>

</html>
