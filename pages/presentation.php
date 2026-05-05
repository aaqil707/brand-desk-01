<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VDart Group Presentation</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
        }

        .header {
            background-color: #fff;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .back-button {
            background-color: #f0f0f0;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-family: inherit;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            transition: background-color 0.2s;
        }

        .presentation-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .presentation-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .controls {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .control-button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            background-color: #fff;
            cursor: pointer;
            font-family: inherit;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .presentation-wrapper {
            position: relative;
            width: 100%;
            height: calc(100vh - 200px);
            min-height: 600px;
        }

        .presentation-wrapper iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
            background: white;
        }

        .fullscreen .presentation-wrapper {
            height: 100vh;
        }

        @media (max-width: 768px) {
            .controls {
                flex-wrap: wrap;
            }

            .control-button {
                flex: 1;
                min-width: 120px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <button onclick="window.location.href='index.php'" class="back-button">
            ← Back to Home
        </button>
        <h1>VDart Group Presentation</h1>
        <p>Interactive Company Overview</p>
    </div>

    <div class="presentation-container">
        <div class="presentation-card" id="presentationCard">
            <div class="controls">
                <button class="control-button" onclick="toggleFullscreen()">
                    <span class="icon">🔍</span> Fullscreen
                </button>
                <button class="control-button" onclick="openInNewTab()">
                    <span class="icon">📝</span> Open in New Tab
                </button>
                <button class="control-button" onclick="downloadPresentation()">
                    <span class="icon">⬇️</span> Download
                </button>
            </div>

            <div class="presentation-wrapper" id="presentationWrapper">
                <iframe 
                    id="presentationFrame"
                    src="https://view.officeapps.live.com/op/embed.aspx?src=https://raw.githubusercontent.com/Saranraj102000/VDart-images/main/profilepicture.pptx"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>

    <script>
        const rawGitUrl = 'https://raw.githubusercontent.com/Saranraj102000/VDart-images/main/profilepicture.pptx';
        
        function toggleFullscreen() {
            const card = document.getElementById('presentationCard');
            card.classList.toggle('fullscreen');
            
            if (card.classList.contains('fullscreen')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'auto';
            }
        }

        function openInNewTab() {
            window.open(rawGitUrl, '_blank');
        }

        function downloadPresentation() {
            window.open(rawGitUrl);
        }

        // Handle escape key for fullscreen
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && document.getElementById('presentationCard').classList.contains('fullscreen')) {
                toggleFullscreen();
            }
        });
    </script>
</body>
</html>