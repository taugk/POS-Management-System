<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installer - Installing System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .step-item { @apply flex items-center gap-3 text-sm transition-all duration-500 py-1; }
        .success-text { @apply text-green-600 font-semibold; }
        .pending-text { @apply text-gray-400; }
        .error-text { @apply text-red-600 font-semibold; }
        .btn-primary { @apply bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 transition block w-full text-center shadow-md active:scale-95; }
        #progressBar { transition: width 0.5s ease-in-out; }
        .debug-box { @apply mt-4 p-3 bg-gray-900 text-gray-300 text-[10px] font-mono rounded overflow-x-auto border-l-4 border-yellow-500; }
    </style>
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">

<div class="max-w-xl w-full bg-white p-10 rounded-2xl shadow-xl text-center">
    
    <div class="mb-8">
        <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg id="mainLoader" class="w-8 h-8 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <svg id="successCheck" class="w-10 h-10 hidden text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h2 id="installTitle" class="text-2xl font-bold text-gray-800">Installing System</h2>
        <p id="installSubtitle" class="text-gray-500 mt-2">Please wait, configuring your system...</p>
    </div>

    <div class="relative pt-1">
        <div class="overflow-hidden h-3 mb-6 text-xs flex rounded-full bg-gray-200">
            <div id="progressBar" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-600" style="width: 5%"></div>
        </div>
    </div>

    <div class="space-y-3 mb-8 text-left border-t border-b py-6" id="stepsContainer">
        <div id="step1" class="step-item text-blue-600 font-medium">
            <span class="status-icon" id="icon1">⏳</span> <span>Connecting to Database...</span>
        </div>
        <div id="step2" class="step-item pending-text">
            <span class="status-icon" id="icon2">⚪</span> <span>Running Migrations...</span>
        </div>
        <div id="step3" class="step-item pending-text">
            <span class="status-icon" id="icon3">⚪</span> <span>Creating Admin Account...</span>
        </div>
        <div id="step4" class="step-item pending-text">
            <span class="status-icon" id="icon4">⚪</span> <span>Finalizing Setup...</span>
        </div>
    </div>

    <div id="actionContainer">
        <button id="finishBtn" disabled class="btn-primary opacity-50 cursor-not-allowed">
            Installing...
        </button>
    </div>

    <div id="errorAlert" class="hidden mt-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-left rounded shadow-sm">
        <p class="font-bold flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            Installation Failed!
        </p>
        <p id="errorMessage" class="text-sm mt-2 font-semibold"></p>
        
        <div id="debugInfo" class="debug-box hidden">
            <div class="text-yellow-500 mb-1 border-b border-gray-700 pb-1 font-bold">DEBUG LOG:</div>
            <div id="debugContent"></div>
        </div>

        <div class="flex gap-4 mt-4">
            <button onclick="window.location.reload()" class="bg-red-600 text-white px-4 py-2 rounded text-xs font-bold hover:bg-red-700 transition">Try Again</button>
            <a href="{{ route('installer.database') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-xs font-bold hover:bg-gray-300 transition text-center">Reconfigure DB</a>
        </div>
    </div>
</div>

<script>
    const progressBar = document.getElementById('progressBar');
    const finishBtn = document.getElementById('finishBtn');
    
    function updateStepUI(stepNumber) {
        const el = document.getElementById('step' + stepNumber);
        const icon = document.getElementById('icon' + stepNumber);
        el.className = 'step-item success-text';
        icon.innerText = '✅';
        progressBar.style.width = (stepNumber * 25) + "%";
    }

    async function startInstallation() {
        console.log("Installer: Requesting process...");
        
        let fakeProgress = 5;
        const progressInterval = setInterval(() => {
            if (fakeProgress < 90) {
                fakeProgress += 1;
                progressBar.style.width = fakeProgress + "%";
            }
        }, 800);

        try {
            const response = await fetch("{{ route('installer.install_process') }}", {
                method: 'GET',
                headers: { 
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();
            clearInterval(progressInterval);

            if (response.ok && result.success) {
                console.log("Installer: Process successful.");
                animateFinalSteps();
            } else {
                console.error("Installer: Server returned an error.", result);
                showErrorUI(
                    result.message || 'Unknown Server Error', 
                    result.debug || 'Check storage/logs/laravel.log for detail trace.'
                );
            }
        } catch (error) {
            console.error("Installer: Network or JS Error.", error);
            clearInterval(progressInterval);
            showErrorUI("Network/Client Error: " + error.message, "Make sure your server is running and accessible.");
        }
    }

    function animateFinalSteps() {
        let step = 1;
        const interval = setInterval(() => {
            updateStepUI(step);
            if (step === 4) {
                clearInterval(interval);
                finishInstallation();
            }
            step++;
        }, 700);
    }

    function finishInstallation() {
        document.getElementById('mainLoader').classList.add('hidden');
        document.getElementById('successCheck').classList.remove('hidden');
        document.getElementById('installTitle').innerText = "Installation Successful!";
        document.getElementById('installSubtitle').innerText = "Redirecting to login dashboard...";
        
        finishBtn.innerText = "Finalizing...";
        finishBtn.classList.replace('bg-blue-600', 'bg-green-600');

        setTimeout(() => {
            window.location.href = "{{ route('login') }}";
        }, 2000);
    }

    function showErrorUI(msg, debug) {
        document.getElementById('errorAlert').classList.remove('hidden');
        document.getElementById('errorMessage').innerText = msg;
        
        if(debug) {
            document.getElementById('debugInfo').classList.remove('hidden');
            document.getElementById('debugContent').innerText = debug;
        }

        progressBar.style.backgroundColor = '#ef4444';
        progressBar.style.width = '100%';
        document.getElementById('mainLoader').classList.add('hidden');
        document.getElementById('stepsContainer').classList.add('opacity-40');
        
        finishBtn.innerText = "Failed";
    }

    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(startInstallation, 1000);
    });
</script>
</body>
</html>