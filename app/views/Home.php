
<?php require_once 'app/views/partials/navbar.php'?>
<!-- <div class="text-4xl text-orange-500 underline">Lets Begin</div> -->


<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Backend Test Controls</h1>

        <div class="mb-6">
            <label for="migrationTest" class="block text-gray-700 text-sm font-bold mb-2">Test Database Migration:</label>
            <button id="runMigrationBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                Run Test Migration
            </button>
        </div>

        <div>
            <label for="emailInput" class="block text-gray-700 text-sm font-bold mb-2">Test Email Submission:</label>
            <input type="email" id="emailInput" placeholder="Enter email for test" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-3">
            <button id="submitEmailBtn" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                Submit Test Email
            </button>
        </div>

        <div id="responseArea" class="mt-6 p-4 bg-gray-50 rounded-md text-gray-700 text-sm hidden">
            <h3 class="font-bold mb-2">Server Response:</h3>
            <pre id="responseData" class="whitespace-pre-wrap font-mono text-xs"></pre>
        </div>
    </div>

    <script>
        const runMigrationBtn = document.getElementById('runMigrationBtn');
        const emailInput = document.getElementById('emailInput');
        const submitEmailBtn = document.getElementById('submitEmailBtn');
        const responseArea = document.getElementById('responseArea');
        const responseData = document.getElementById('responseData');

        // Replace with your actual backend URL
        const BACKEND_URL = 'Test'; // Example backend endpoint

        async function sendData(action, payload = {}) {
            try {
                responseArea.classList.add('hidden'); // Hide previous response
                responseData.textContent = 'Loading...'; // Show loading state

                const response = await fetch(BACKEND_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ action, ...payload }),
                });

                const data = await response.json();
                responseData.textContent = JSON.stringify(data, null, 2);
                responseArea.classList.remove('hidden'); // Show response area
            } catch (error) {
                responseData.textContent = `Error: ${error.message}`;
                responseArea.classList.remove('hidden');
            }
        }

        runMigrationBtn.addEventListener('click', () => {
            sendData('runMigration');
        });

        submitEmailBtn.addEventListener('click', () => {
            const email = emailInput.value;
            if (email) {
                sendData('submitEmail', { email });
            } else {
                alert('Please enter an email address.');
            }
        });
    </script>
</body>
</html>