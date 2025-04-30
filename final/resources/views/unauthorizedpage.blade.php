<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied | Unauthorized</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;350;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            background-image: radial-gradient(at 100% 0%, rgba(239, 246, 255, 0.4) 0px, transparent 50%),
                              radial-gradient(at 0% 100%, rgba(224, 231, 255, 0.2) 0px, transparent 50%);
        }
        .card {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            border: 1px solid rgba(226, 232, 240, 0.6);
        }
        .btn {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            letter-spacing: 0.01em;
        }
        .icon-circle {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(248, 250, 252, 0.9) 100%);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        }
        .forbidden-icon {
            stroke-dasharray: 1000;
            stroke-dashoffset: 1000;
            animation: draw 1.6s ease-out forwards;
        }
        @keyframes draw {
            to { stroke-dashoffset: 0; }
        }
    </style>
</head>
<body class="h-screen flex items-center justify-center px-4">
    <div class="card bg-white/95 p-12 rounded-2xl max-w-md w-full text-center">
        <div class="icon-circle mx-auto flex items-center justify-center h-28 w-28 rounded-full mb-8 border border-gray-100/30">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-gray-500/90">
                <path d="M12 15V15.01M6 21H18C19.1046 21 20 20.1046 20 19V13C20 11.8954 19.1046 11 18 11H6C4.89543 11 4 11.8954 4 13V19C4 20.1046 4.89543 21 6 21Z" 
                      stroke="currentColor" 
                      stroke-width="1.5" 
                      stroke-linecap="round" 
                      stroke-linejoin="round"
                      class="forbidden-icon"/>
                <path d="M16 11V8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8V11" 
                      stroke="currentColor" 
                      stroke-width="1.5" 
                      stroke-linecap="round" 
                      stroke-linejoin="round"
                      class="forbidden-icon"
                      style="animation-delay: 0.3s"/>
                <path d="M4 12L20 12" 
                      stroke="currentColor" 
                      stroke-width="1.5" 
                      stroke-linecap="round" 
                      class="forbidden-icon"
                      style="animation-delay: 0.6s; opacity: 0.7"
                      transform="rotate(45 12 12)"/>
            </svg>
        </div>
        
        <h1 class="text-3xl font-light text-gray-800/95 mb-3 tracking-tight">Access Forbidden</h1>
        <p class="text-gray-500/80 mb-10 max-w-xs mx-auto leading-relaxed tracking-wide">
            Authorization required to access this resource. Please verify your credentials.
        </p>
        
        <div class="flex justify-center">
            <a href="{{ url('/unauthorized2') }}" class="btn px-8 py-3.5 rounded-xl bg-gray-800 text-white hover:bg-gray-700 font-medium text-sm tracking-wide shadow-sm hover:shadow transition-all duration-300 transform hover:-translate-y-0.5">
                Return to Dashboard
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline ml-2 -mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
        
        <p class="mt-12 text-xs text-gray-400/70 tracking-wider">
            ERROR 403 • UNAUTHORIZED ACCESS • <span class="font-mono opacity-80">{{ now()->format('H:i') }}</span>
        </p>
    </div>
</body>
</html>