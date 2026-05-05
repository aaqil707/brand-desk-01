<!-- Preview Section -->
<section class="preview-section">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Preview</h1>
        <div class="flex items-center gap-4">
            <!-- Help Button -->
            <button class="help-icon" aria-label="Help">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="10"/>
                    <text x="12" y="17" text-anchor="middle" font-size="14" fill="currentColor" stroke="none">?</text>
                </svg>
                <span class="tooltip">Need help? Click for guidance on creating your signature</span>
            </button>
            
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Auto-refresh</span>
                <label class="switch">
                    <input type="checkbox" id="autoRefresh" checked>
                    <span class="slider"></span>
                </label>
            </div>
        </div>
    </div>

    <div class="tab-container">
        <button class="tab-button active" data-tab="outlook">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
                Outlook
            </span>
        </button>
        <button class="tab-button" data-tab="ceipal">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                CEIPAL
            </span>
        </button>
    </div>

    <div class="preview-content bg-white rounded-lg shadow-lg p-6 mb-6">
        <div id="signature-preview" class="min-h-[200px]">
            <div class="text-gray-400 text-center py-10">
                Fill out the form to generate your signature
            </div>
        </div>
    </div>

    <button id="copyButton" class="copy-btn w-full opacity-50 cursor-not-allowed" disabled>
        <h1 style="font-size: 20px;">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                </svg>
                Copy Signature
            </span>
        </h1>
    </button>
    
</section>