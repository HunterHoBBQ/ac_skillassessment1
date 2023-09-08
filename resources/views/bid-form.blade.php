<!-- resources/views/bid-form.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <title>Bid Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        #bid-form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        #response {
            margin-top: 20px;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: none;
        }

        #response.success {
            background-color: #4CAF50;
            color: white;
        }

        #response.error {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Submit Your Bid</h1>

    <form id="bid-form" action="/bid" method="POST">
        @csrf
        <label for="user_id">User ID:</label>
        <input type="text" id="user_id" name="user_id" required><br>

        <label for="price">Bid Price:</label>
        <input type="number" id="price" name="price" step="0.01" required><br>

        <button type="submit">Submit Bid</button>
    </form>

    <div id="response" class="success"></div>

    <script>
        document.getElementById('bid-form').addEventListener('submit', function (event) {
            event.preventDefault();

            // Validate the price input for two decimal places
        // Automatically format the price input to have two decimal places
        const priceInput = document.getElementById('price');
        priceInput.value = parseFloat(priceInput.value).toFixed(2);
    
            const formData = new FormData(this);
    
            fetch('/bid', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => response.json())
            .then(data => {
                const responseDiv = document.getElementById('response');
                responseDiv.style.display = 'block';
    
                if (data.message === 'Success') {
                    // Handle success
                    responseDiv.innerHTML = `Bid submitted successfully! 
                    User: ${data.data.full_name}, Price: ${data.data.price}`;
                    responseDiv.className = 'success';
                } else if (data.errors && data.errors.price) {
                    // Handle price validation errors
                    responseDiv.innerHTML = `Validation failed: ${data.errors.price[0]}`;
                    responseDiv.className = 'error';
                } else if (data.message === 'User not found') {
                    // Handle user not found error
                    responseDiv.innerHTML = 'User not found';
                    responseDiv.className = 'error';
                } else {
                    // Handle other errors
                    let errorMessage = data.message || 'Unknown error occurred.';
                    responseDiv.innerHTML = errorMessage;
                    responseDiv.className = 'error';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const responseDiv = document.getElementById('response');
                responseDiv.innerHTML = 'An error occurred while processing your request.';
                responseDiv.className = 'error';
                responseDiv.style.display = 'block';
            });
        });
    </script>
</body>
</html>
