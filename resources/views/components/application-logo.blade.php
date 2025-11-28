<svg viewBox="100 80 200 240" xmlns="http://www.w3.org/2000/svg" {{ $attributes }} fill="none">
    <defs>
        <linearGradient id="techGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#6366F1"/> <stop offset="100%" stop-color="#8B5CF6"/> </linearGradient>

        <linearGradient id="accentGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#22D3EE"/> <stop offset="100%" stop-color="#0EA5E9"/> </linearGradient>

        <filter id="softShadow" x="-50%" y="-50%" width="200%" height="200%">
            <feGaussianBlur in="SourceAlpha" stdDeviation="4"/>
            <feOffset dx="2" dy="4" result="offsetblur"/>
            <feComponentTransfer>
                <feFuncA type="linear" slope="0.2"/>
            </feComponentTransfer>
            <feMerge>
                <feMergeNode/>
                <feMergeNode in="SourceGraphic"/>
            </feMerge>
        </filter>
    </defs>

    <g transform="translate(80, 80)" filter="url(#softShadow)">

        <path d="M50 210 V50 L190 210 V85"
              stroke="url(#techGradient)"
              stroke-width="45"
              stroke-linecap="round"
              stroke-linejoin="round"/>

        <g>
            <path d="M190 95 C190 95 162 65 162 48 C162 31 174.5 18 190 18 C205.5 18 218 31 218 48 C218 65 190 95 190 95Z"
                  fill="url(#accentGradient)"
                  stroke="#ffffff"
                  stroke-width="8"
                  stroke-linejoin="round"/>

            <circle cx="190" cy="48" r="8" fill="#ffffff"/>
        </g>

    </g>
</svg>
