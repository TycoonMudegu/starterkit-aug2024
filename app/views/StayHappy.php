<?php require_once 'app/views/head.php';
$PageTitle = "🚗 Enhanced Knockdown Game";
?>

    <title>🚗 Enhanced Knockdown Game</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            overflow: hidden;
            color: white;
        }
        
        #loading-screen {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            transition: opacity 0.5s ease;
        }
        
        .loading-spinner {
            width: 50px; height: 50px;
            border: 4px solid rgba(255,255,255,0.3);
            border-top: 4px solid #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .loading-text {
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        .loading-progress {
            width: 300px;
            height: 6px;
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #00ff88, #00cc6a);
            width: 0%;
            transition: width 0.3s ease;
        }
        
        .error-message {
            color: #ff4444;
            background: rgba(255,68,68,0.1);
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            max-width: 600px;
            text-align: center;
            border: 1px solid rgba(255,68,68,0.3);
        }
        
        #game-ui {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 100;
            background: rgba(0,0,0,0.7);
            padding: 20px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            min-width: 200px;
        }
        
        .ui-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #00ff88;
        }
        
        .ui-stat {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 16px;
        }
        
        .ui-controls {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(255,255,255,0.2);
            font-size: 14px;
            color: #ccc;
        }
        
        #game-over {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.9);
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            z-index: 200;
            display: none;
            backdrop-filter: blur(10px);
            border: 2px solid #00ff88;
        }
        
        .game-over-title {
            font-size: 32px;
            color: #00ff88;
            margin-bottom: 20px;
        }
        
        .final-score {
            font-size: 24px;
            margin-bottom: 20px;
        }
        
        .restart-btn {
            background: linear-gradient(45deg, #00ff88, #00cc6a);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 18px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .restart-btn:hover {
            transform: scale(1.05);
        }
        
        canvas {
            display: block;
            cursor: crosshair;
        }
        
        .hidden { display: none !important; }
    </style>
</head>
<body>
    <div id="loading-screen">
        <div class="loading-spinner"></div>
        <div class="loading-text">Loading Enhanced Knockdown Game...</div>
        <div class="loading-progress">
            <div class="progress-bar" id="progress-bar"></div>
        </div>
        <div id="loading-status">Initializing...</div>
        <div id="error-display" class="hidden error-message"></div>
    </div>

    <div id="game-ui" class="hidden">
        <div class="ui-title">🚗 Knockdown</div>
        <div class="ui-stat">
            <span>Score:</span>
            <span id="score">0</span>
        </div>
        <div class="ui-stat">
            <span>Blocks:</span>
            <span id="blocks-remaining">20</span>
        </div>
        <div class="ui-stat">
            <span>Speed:</span>
            <span id="speed">0 km/h</span>
        </div>
        <div class="ui-stat">
            <span>Time:</span>
            <span id="time">0:00</span>
        </div>
        <div class="ui-controls">
            <div><strong>Controls:</strong></div>
            <div>W/S - Forward/Backward</div>
            <div>A/D - Steer Left/Right</div>
            <div>Space - Handbrake</div>
            <div>R - Reset Car</div>
        </div>
    </div>

    <div id="game-over">
        <div class="game-over-title">🎉 Level Complete!</div>
        <div class="final-score">Final Score: <span id="final-score">0</span></div>
        <div>Time: <span id="final-time">0:00</span></div>
        <button class="restart-btn" onclick="restartGame()">Play Again</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/three-js@79.0.0/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cannon.js/0.6.2/cannon.min.js"></script>
    <script>
        // Error handling and logging system
        class Logger {
            static log(message, type = 'info') {
                const timestamp = new Date().toISOString();
                const logMessage = `[${timestamp}] [${type.toUpperCase()}] ${message}`;
                console.log(logMessage);
                
                if (type === 'error') {
                    this.showError(message);
                }
            }
            
            static showError(message) {
                const errorDisplay = document.getElementById('error-display');
                errorDisplay.textContent = `ERROR: ${message}`;
                errorDisplay.classList.remove('hidden');
            }
            
            static hideError() {
                const errorDisplay = document.getElementById('error-display');
                errorDisplay.classList.add('hidden');
            }
        }

        // Game state management
        class GameState {
            constructor() {
                this.score = 0;
                this.blocksRemaining = 20;
                this.startTime = Date.now();
                this.gameRunning = false;
                this.gameComplete = false;
            }
            
            updateScore(points) {
                this.score += points;
                document.getElementById('score').textContent = this.score;
            }
            
            removeBlock() {
                this.blocksRemaining--;
                document.getElementById('blocks-remaining').textContent = this.blocksRemaining;
                
                if (this.blocksRemaining <= 0) {
                    this.completeGame();
                }
            }
            
            updateSpeed(speed) {
                document.getElementById('speed').textContent = `${Math.round(speed)} km/h`;
            }
            
            updateTime() {
                if (!this.gameRunning) return;
                
                const elapsed = Math.floor((Date.now() - this.startTime) / 1000);
                const minutes = Math.floor(elapsed / 60);
                const seconds = elapsed % 60;
                document.getElementById('time').textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            }
            
            completeGame() {
                this.gameRunning = false;
                this.gameComplete = true;
                
                document.getElementById('final-score').textContent = this.score;
                document.getElementById('final-time').textContent = document.getElementById('time').textContent;
                document.getElementById('game-over').style.display = 'block';
                
                Logger.log(`Game completed! Score: ${this.score}, Time: ${document.getElementById('time').textContent}`);
            }
        }

        // Global variables
        let scene, camera, renderer, world;
        let carMesh, carBody;
        let blocks = [];
        let gameState = new GameState();
        let keys = {};
        let audioContext;
        let crashSounds = [];

        // Initialize loading screen
        const progressBar = document.getElementById('progress-bar');
        const loadingStatus = document.getElementById('loading-status');
        let loadingProgress = 0;

        function updateLoadingProgress(step, total, message) {
            loadingProgress = (step / total) * 100;
            progressBar.style.width = `${loadingProgress}%`;
            loadingStatus.textContent = message;
        }

        // Audio creation function
        function createCrashSound() {
            if (!audioContext) return null;
            
            return () => {
                try {
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();
                    
                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);
                    
                    oscillator.frequency.setValueAtTime(200, audioContext.currentTime);
                    oscillator.frequency.exponentialRampToValueAtTime(50, audioContext.currentTime + 0.1);
                    
                    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
                    
                    oscillator.start();
                    oscillator.stop(audioContext.currentTime + 0.1);
                } catch (e) {
                    console.log('Audio not available');
                }
            };
        }

        // Initialize game
        function initializeGame() {
            try {
                updateLoadingProgress(1, 6, 'Initializing 3D Scene...');
                
                // Create scene
                scene = new THREE.Scene();
                scene.background = new THREE.Color(0x87CEEB);
                scene.fog = new THREE.Fog(0x87CEEB, 50, 200);
                
                // Create camera
                camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
                camera.position.set(0, 8, 15);
                
                // Create renderer
                renderer = new THREE.WebGLRenderer({ antialias: true });
                renderer.setSize(window.innerWidth, window.innerHeight);
                renderer.shadowMap.enabled = true;
                renderer.shadowMap.type = THREE.PCFSoftShadowMap;
                document.body.appendChild(renderer.domElement);
                
                updateLoadingProgress(2, 6, 'Initializing Physics...');
                
                // Create physics world
                world = new CANNON.World();
                world.gravity.set(0, -9.82, 0);
                world.broadphase = new CANNON.NaiveBroadphase();
                world.solver.iterations = 10;
                
                updateLoadingProgress(3, 6, 'Setting up Audio...');
                
                // Initialize audio
                try {
                    audioContext = new (window.AudioContext || window.webkitAudioContext)();
                    for (let i = 0; i < 5; i++) {
                        crashSounds.push(createCrashSound());
                    }
                } catch (error) {
                    Logger.log('Audio system not available', 'warn');
                }
                
                updateLoadingProgress(4, 6, 'Creating Game Objects...');
                
                setupLighting();
                setupGround();
                setupCar();
                setupBlocks();
                setupControls();
                
                updateLoadingProgress(5, 6, 'Finalizing...');
                
                gameState.gameRunning = true;
                gameState.startTime = Date.now();
                
                updateLoadingProgress(6, 6, 'Ready!');
                
                setTimeout(() => {
                    document.getElementById('loading-screen').style.opacity = '0';
                    setTimeout(() => {
                        document.getElementById('loading-screen').remove();
                        document.getElementById('game-ui').classList.remove('hidden');
                        animate();
                    }, 500);
                }, 1000);
                
                Logger.log('Game initialized successfully');
                
            } catch (error) {
                Logger.log(`Game initialization failed: ${error.message}`, 'error');
            }
        }

        function setupLighting() {
            const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
            directionalLight.position.set(10, 20, 10);
            directionalLight.castShadow = true;
            directionalLight.shadow.mapSize.width = 2048;
            directionalLight.shadow.mapSize.height = 2048;
            directionalLight.shadow.camera.near = 0.1;
            directionalLight.shadow.camera.far = 50;
            directionalLight.shadow.camera.left = -20;
            directionalLight.shadow.camera.right = 20;
            directionalLight.shadow.camera.top = 20;
            directionalLight.shadow.camera.bottom = -20;
            scene.add(directionalLight);
            
            const ambientLight = new THREE.AmbientLight(0x404040, 0.4);
            scene.add(ambientLight);
        }

        function setupGround() {
            const groundGeometry = new THREE.PlaneGeometry(100, 100);
            const groundMaterial = new THREE.MeshLambertMaterial({ color: 0x90EE90 });
            const groundMesh = new THREE.Mesh(groundGeometry, groundMaterial);
            groundMesh.rotation.x = -Math.PI / 2;
            groundMesh.receiveShadow = true;
            scene.add(groundMesh);
            
            const groundShape = new CANNON.Plane();
            const groundBody = new CANNON.Body({ mass: 0 });
            groundBody.addShape(groundShape);
            groundBody.quaternion.setFromAxisAngle(new CANNON.Vec3(1, 0, 0), -Math.PI / 2);
            world.add(groundBody);
        }

        function setupCar() {
            const carGeometry = new THREE.BoxGeometry(2, 1, 4);
            const carMaterial = new THREE.MeshLambertMaterial({ color: 0xff0000 });
            carMesh = new THREE.Mesh(carGeometry, carMaterial);
            carMesh.position.set(0, 1, 0);
            carMesh.castShadow = true;
            scene.add(carMesh);
            
            const carShape = new CANNON.Box(new CANNON.Vec3(1, 0.5, 2));
            carBody = new CANNON.Body({ mass: 500 });
            carBody.addShape(carShape);
            carBody.position.set(0, 1, 0);
            carBody.material = new CANNON.Material({ friction: 0.4, restitution: 0.3 });
            world.add(carBody);
        }

        function setupBlocks() {
            const blockGeometry = new THREE.BoxGeometry(1, 1, 1);
            
            for (let i = 0; i < 20; i++) {
                const hue = (i * 18) % 360;
                const color = new THREE.Color();
                color.setHSL(hue / 360, 0.7, 0.5);
                
                const blockMaterial = new THREE.MeshLambertMaterial({ color: color });
                const blockMesh = new THREE.Mesh(blockGeometry, blockMaterial);
                
                const x = (Math.random() - 0.5) * 40;
                const z = (Math.random() - 0.5) * 40;
                blockMesh.position.set(x, 0.5, z);
                blockMesh.castShadow = true;
                scene.add(blockMesh);
                
                const blockShape = new CANNON.Box(new CANNON.Vec3(0.5, 0.5, 0.5));
                const blockBody = new CANNON.Body({ mass: 1 });
                blockBody.addShape(blockShape);
                blockBody.position.set(x, 0.5, z);
                blockBody.material = new CANNON.Material({ friction: 0.4, restitution: 0.6 });
                world.add(blockBody);
                
                blocks.push({ mesh: blockMesh, body: blockBody, destroyed: false });
            }
        }

        function setupControls() {
            document.addEventListener('keydown', (event) => {
                keys[event.key.toLowerCase()] = true;
                if (event.key === ' ') event.preventDefault();
            });
            
            document.addEventListener('keyup', (event) => {
                keys[event.key.toLowerCase()] = false;
            });
        }

        function handleBlockCollision(blockIndex) {
            const block = blocks[blockIndex];
            if (block.destroyed) return;
            
            block.destroyed = true;
            
            // Visual destruction effect
            scene.remove(block.mesh);
            world.remove(block.body);
            
            // Play crash sound
            if (crashSounds.length > 0) {
                const soundIndex = Math.floor(Math.random() * crashSounds.length);
                if (crashSounds[soundIndex]) {
                    crashSounds[soundIndex]();
                }
            }
            
            // Update game state
            gameState.updateScore(100);
            gameState.removeBlock();
            
            Logger.log(`Block ${blockIndex} destroyed! Score: ${gameState.score}`);
        }

        function updateCarControls() {
            if (!carBody || !gameState.gameRunning) return;

            // Adjust these values for desired car behavior
            const engineForce = 1500; // Force for acceleration
            const brakeForce = 500;  // Force for braking/handbrake
            const steeringSpeed = 0.05; // How quickly the car turns
            const maxSteeringAngle = Math.PI / 8; // Maximum steering angle (radians)

            // Linear damping to simulate air resistance and friction when not accelerating
            carBody.linearDamping = 0.9; // Higher value means more damping (0-1)
            carBody.angularDamping = 0.8; // Damping for rotation

            // Forward/Backward movement
            if (keys['w']) {
                // Apply force along the car's local Z-axis (forward)
                carBody.applyLocalForce(new CANNON.Vec3(0, 0, -engineForce), new CANNON.Vec3(0, 0, 0));
            }
            if (keys['s']) {
                // Apply force along the car's local Z-axis (backward)
                carBody.applyLocalForce(new CANNON.Vec3(0, 0, engineForce), new CANNON.Vec3(0, 0, 0));
            }

            // Steering (Applying torque around the local Y-axis)
            if (keys['a']) {
                // Apply positive torque (turn left)
                carBody.angularVelocity.y += steeringSpeed;
            }
            if (keys['d']) {
                // Apply negative torque (turn right)
                carBody.angularVelocity.y -= steeringSpeed;
            }

            // Handbrake / Braking
            if (keys[' ']) {
                // Apply a strong braking force against the current velocity
                const currentVelocity = carBody.velocity.clone();
                currentVelocity.negate(); // Reverse the velocity direction
                currentVelocity.normalize(); // Get direction only

                // Apply braking force in the opposite direction of current velocity
                // We'll apply it scaled by brakeForce
                carBody.applyForce(currentVelocity.scale(brakeForce), carBody.position);

                // Also reduce angular velocity for a more complete stop
                carBody.angularVelocity.scale(0.8); // Reduce angular velocity
            }

            // Reset Car
            if (keys['r']) {
                carBody.position.set(0, 2, 0);
                carBody.velocity.set(0, 0, 0);
                carBody.angularVelocity.set(0, 0, 0);
                carBody.quaternion.set(0, 0, 0, 1);
                Logger.log('Car reset to start position.');
            }

            // Update speed display
            const speed = carBody.velocity.length() * 3.6; // Convert to km/h
            gameState.updateSpeed(speed);
        }

        function checkCollisions() {
            if (!carBody) return;
            
            blocks.forEach((block, index) => {
                if (block.destroyed) return;
                
                const distance = carBody.position.distanceTo(block.body.position);
                if (distance < 2) {
                    handleBlockCollision(index);
                }
            });
        }

        function animate() {
            requestAnimationFrame(animate);
            
            if (!gameState.gameRunning) return;
            
            updateCarControls();
            checkCollisions();
            world.step(1/60);
            
            // Update car mesh position
            if (carMesh && carBody) {
                carMesh.position.copy(carBody.position);
                carMesh.quaternion.copy(carBody.quaternion);
            }
            
            // Update block positions
            blocks.forEach(block => {
                if (!block.destroyed) {
                    block.mesh.position.copy(block.body.position);
                    block.mesh.quaternion.copy(block.body.quaternion);
                }
            });
            
            // Update camera to follow car
            if (carBody) {
                const targetPosition = new THREE.Vector3(
                    carBody.position.x,
                    carBody.position.y + 8,
                    carBody.position.z + 15
                );
                camera.position.lerp(targetPosition, 0.1);
                camera.lookAt(carBody.position);
            }
            
            // Update UI
            gameState.updateTime();
            
            renderer.render(scene, camera);
        }

        // Restart game function
        function restartGame() {
            location.reload();
        }

        // Handle window resize
        window.addEventListener('resize', () => {
            if (camera && renderer) {
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(window.innerWidth, window.innerHeight);
            }
        });

        // Start the game when page loads
        window.addEventListener('load', () => {
            setTimeout(initializeGame, 500);
        });
    </script>
</body>
</html>