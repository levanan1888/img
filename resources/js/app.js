import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    // -------------------------------------------------------------
    // DOM Elements
    // -------------------------------------------------------------
    const dropzone = document.getElementById('upload-dropzone');
    const fileInput = document.getElementById('file-input');
    const selectButton = document.getElementById('select-file-btn');
    
    // View Containers
    const stateIdle = document.getElementById('state-idle');
    const stateUploading = document.getElementById('state-uploading');
    const stateConverting = document.getElementById('state-converting');
    const stateSuccess = document.getElementById('state-success');
    
    // Upload Progress Elements
    const cardFileName = document.getElementById('card-file-name');
    const cardFileSize = document.getElementById('card-file-size');
    const cardProgress = document.getElementById('card-progress');
    const cardProgressBar = document.getElementById('card-progress-bar');
    const cardSpeed = document.getElementById('card-speed');
    const cardRemaining = document.getElementById('card-remaining');
    const cardStatusText = document.getElementById('card-status-text');
    const btnReplaceFile = document.getElementById('btn-replace-file');
    const btnCancelUpload = document.getElementById('btn-cancel-upload');
    const btnRetryUpload = document.getElementById('btn-retry-upload');
    
    // Target Format Selector
    const targetFormatSelect = document.getElementById('target-format-select');
    
    // Settings Toggles & CTAs
    const settingsPanel = document.getElementById('settings-panel');
    const settingsToggleBtn = document.getElementById('settings-toggle-btn');
    const settingsToggleIcon = document.getElementById('settings-toggle-icon');
    const convertCtaContainer = document.getElementById('convert-cta-container');
    const btnConvert = document.getElementById('btn-convert');
    
    // Console Logs / Steps
    const procProgressBar = document.getElementById('proc-progress-bar');
    const procProgressPercent = document.getElementById('proc-progress-percent');
    const procTimeRemaining = document.getElementById('proc-time-remaining');
    const consoleTerminal = document.getElementById('console-terminal');
    
    const stepsData = [
        { id: 'step-uploading', label: 'UPLOADING', msg: 'Uploading image stream to edge server...' },
        { id: 'step-preparing', label: 'PREPARING', msg: 'Initializing sandbox environment workspace...' },
        { id: 'step-reading', label: 'READING', msg: 'Decoding pixel buffer arrays and header maps...' },
        { id: 'step-converting', label: 'CONVERTING', msg: 'Rendering lossless transparency color channels...' },
        { id: 'step-optimizing', label: 'OPTIMIZING', msg: 'Applying compression vectors...' },
        { id: 'step-generating', label: 'GENERATING', msg: 'Encoding pixel vectors to lossless stream...' },
        { id: 'step-finalizing', label: 'FINALIZING', msg: 'Terminating container. Purging cached workspaces...' }
    ];
    
    // Success Elements
    const successFileName = document.getElementById('success-file-name');
    const successFileSize = document.getElementById('success-file-size');
    const successTime = document.getElementById('success-time');
    const successCompression = document.getElementById('success-compression');
    const successSymbol = document.getElementById('success-symbol');
    const btnDownload = document.getElementById('btn-download');
    const btnPreview = document.getElementById('btn-preview');
    const btnCopyLink = document.getElementById('btn-copy-link');
    const btnShare = document.getElementById('btn-share');
    const btnConvertAnother = document.getElementById('btn-convert-another');
    
    // Toast Notification
    const toast = document.getElementById('toast-notification');
    const toastMessage = document.getElementById('toast-message');
    
    // Interactive Splitscreen Comparison Slider
    const splitSlider = document.getElementById('split-range-input');
    const splitPaneRight = document.getElementById('split-pane-right');
    const splitDividerLine = document.getElementById('split-divider-line');
    
    // State variables
    let selectedFile = null;
    let uploadTimer = null;
    let conversionTimer = null;
    let currentUploadProgress = 0;
    let targetFormat = 'png';
    
    // -------------------------------------------------------------
    // Target Format Dropdown Listener
    // -------------------------------------------------------------
    if (targetFormatSelect) {
        targetFormatSelect.addEventListener('change', () => {
            targetFormat = targetFormatSelect.value;
            if (btnConvert) {
                btnConvert.textContent = 'Convert to ' + targetFormat.toUpperCase();
            }
        });
    }

    // -------------------------------------------------------------
    // Segmented Controls (Custom Tab Toggles)
    // -------------------------------------------------------------
    document.querySelectorAll('.segmented-control').forEach(control => {
        const tabs = control.querySelectorAll('.segmented-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                // Track value change (if hidden input exists)
                const targetInputId = control.getAttribute('data-input');
                if (targetInputId) {
                    const hiddenInput = document.getElementById(targetInputId);
                    if (hiddenInput) {
                        hiddenInput.value = tab.getAttribute('data-value');
                    }
                }
            });
        });
    });
    
    // -------------------------------------------------------------
    // FAQ Accordion Handler (Smooth Panel Heights)
    // -------------------------------------------------------------
    document.querySelectorAll('.faq-item').forEach(item => {
        const trigger = item.querySelector('.faq-trigger');
        const panel = item.querySelector('.faq-panel');
        const icon = item.querySelector('.faq-icon');
        
        trigger.addEventListener('click', () => {
            const isOpen = panel.style.maxHeight && panel.style.maxHeight !== '0px';
            
            // Close other items
            document.querySelectorAll('.faq-panel').forEach(p => {
                p.style.maxHeight = '0px';
                p.style.paddingBottom = '0px';
            });
            document.querySelectorAll('.faq-icon').forEach(i => {
                i.style.transform = 'rotate(0deg)';
            });
            
            if (!isOpen) {
                panel.style.maxHeight = panel.scrollHeight + 16 + 'px';
                panel.style.paddingBottom = '16px';
                icon.style.transform = 'rotate(180deg)';
            } else {
                panel.style.maxHeight = '0px';
                panel.style.paddingBottom = '0px';
                icon.style.transform = 'rotate(0deg)';
            }
        });
    });
    
    // -------------------------------------------------------------
    // Split Viewport Drag Slider
    // -------------------------------------------------------------
    if (splitSlider && splitPaneRight && splitDividerLine) {
        const updateSlider = (val) => {
            const percent = val;
            splitPaneRight.style.width = (100 - percent) + '%';
            splitDividerLine.style.left = percent + '%';
        };
        
        splitSlider.addEventListener('input', (e) => {
            updateSlider(e.target.value);
        });
        
        // Initial setup
        updateSlider(50);
    }
    
    // -------------------------------------------------------------
    // Helper Functions
    // -------------------------------------------------------------
    function formatBytes(bytes, decimals = 1) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
    
    function showToast(message) {
        toastMessage.textContent = message;
        toast.classList.remove('translate-y-12', 'opacity-0', 'pointer-events-none');
        toast.classList.add('translate-y-0', 'opacity-100');
        
        setTimeout(() => {
            toast.classList.add('translate-y-12', 'opacity-0', 'pointer-events-none');
            toast.classList.remove('translate-y-0', 'opacity-100');
        }, 3000);
    }
    
    function getTimestamp() {
        const d = new Date();
        return d.toTimeString().split(' ')[0];
    }
    
    // -------------------------------------------------------------
    // View State Manager
    // -------------------------------------------------------------
    function setViewState(state) {
        stateIdle.classList.add('hidden');
        stateUploading.classList.add('hidden');
        stateConverting.classList.add('hidden');
        stateSuccess.classList.add('hidden');
        
        if (state === 'idle') {
            stateIdle.classList.remove('hidden');
            convertCtaContainer.classList.add('hidden');
            if (settingsPanel) settingsPanel.classList.add('hidden');
            if (settingsToggleIcon) settingsToggleIcon.style.transform = 'rotate(0deg)';
            fileInput.value = '';
            selectedFile = null;
            targetFormat = 'png';
            if (targetFormatSelect) targetFormatSelect.value = 'png';
            if (btnConvert) btnConvert.textContent = 'Convert to PNG';
        } else if (state === 'uploading') {
            stateUploading.classList.remove('hidden');
            convertCtaContainer.classList.remove('hidden');
        } else if (state === 'converting') {
            stateConverting.classList.remove('hidden');
            convertCtaContainer.classList.add('hidden');
            if (settingsPanel) settingsPanel.classList.add('hidden');
        } else if (state === 'success') {
            stateSuccess.classList.remove('hidden');
            convertCtaContainer.classList.add('hidden');
            if (settingsPanel) settingsPanel.classList.add('hidden');
        }
    }
    
    // -------------------------------------------------------------
    // File Upload Handler
    // -------------------------------------------------------------
    function handleFileSelected(file) {
        if (!file) return;
        
        const fileExt = file.name.split('.').pop().toLowerCase();
        const allowed = ['jpg', 'jpeg', 'png', 'webp', 'bmp'];
        if (!allowed.includes(fileExt)) {
            showToast('Only JPG, JPEG, PNG, WEBP, and BMP files are supported.');
            return;
        }
        
        if (file.size > 20 * 1024 * 1024) {
            showToast('File must not exceed 20 MB.');
            return;
        }
        
        selectedFile = file;
        setViewState('uploading');
        
        cardFileName.textContent = file.name;
        cardFileSize.textContent = formatBytes(file.size);
        
        const cardSymbol = document.getElementById('card-symbol');
        if (cardSymbol) {
            cardSymbol.textContent = fileExt.toUpperCase();
        }
        
        currentUploadProgress = 0;
        cardProgressBar.style.width = '0%';
        cardProgress.textContent = '0%';
        cardSpeed.textContent = 'Connecting...';
        cardRemaining.textContent = '--';
        
        btnReplaceFile.classList.add('hidden');
        btnCancelUpload.classList.remove('hidden');
        
        if (uploadTimer) clearInterval(uploadTimer);
        
        const totalDuration = 1000;
        const interval = 40;
        const step = (interval / totalDuration) * 100;
        const speed = (6.2 + Math.random() * 3).toFixed(1);
        cardSpeed.textContent = `${speed} MB/s`;
        
        uploadTimer = setInterval(() => {
            currentUploadProgress += step;
            if (currentUploadProgress >= 100) {
                currentUploadProgress = 100;
                clearInterval(uploadTimer);
                cardProgressBar.style.width = '100%';
                cardProgress.textContent = '100%';
                cardSpeed.textContent = 'Streams uploaded';
                cardRemaining.textContent = '0s';
                cardStatusText.textContent = 'Ready';
                cardStatusText.className = 'text-xs font-semibold text-emerald-600';
                
                btnConvert.removeAttribute('disabled');
            } else {
                cardProgressBar.style.width = `${currentUploadProgress.toFixed(0)}%`;
                cardProgress.textContent = `${currentUploadProgress.toFixed(0)}%`;
                
                const remainingBytes = file.size * (1 - (currentUploadProgress / 100));
                const speedBytes = speed * 1024 * 1024;
                const remainingSeconds = Math.ceil(remainingBytes / speedBytes);
                cardRemaining.textContent = `${remainingSeconds}s remaining`;
            }
        }, interval);
    }
    
    async function convertSelectedFileOnServer(appendLog) {
        if (!selectedFile) {
            throw new Error('Please select an image file before converting.');
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const formData = new FormData();
        formData.append('document', selectedFile);
        formData.append('target_format', targetFormat);

        appendLog('info', `SERVER CONVERTER: Uploading image to Laravel converter targeting ${targetFormat.toUpperCase()}...`);

        const response = await fetch('/convert/word-to-pdf', {
            method: 'POST',
            headers: {
                Accept: 'image/png, image/jpeg, image/webp, image/bmp, application/json',
                ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
            },
            body: formData,
        });

        if (!response.ok) {
            const errorPayload = await response.json().catch(() => null);
            const message = errorPayload?.message || 'Unable to convert this image.';
            throw new Error(message);
        }

        const blob = await response.blob();

        if (!blob.size) {
            throw new Error('The generated file is empty. Please try another image file.');
        }

        appendLog('success', `SERVER CONVERTER: Laravel generated a ${targetFormat.toUpperCase()} image successfully.`);

        return {
            blob,
            downloadUrl: URL.createObjectURL(blob),
        };
    }
    
    // -------------------------------------------------------------
    // Timeline Console Progress Simulator
    // -------------------------------------------------------------
    function runConversionSimulation() {
        setViewState('converting');
        
        // Reset Terminal
        consoleTerminal.innerHTML = '';
        
        // Reset all steps layout in DOM
        stepsData.forEach(step => {
            const dot = document.getElementById(`${step.id}-dot`);
            if (dot) dot.className = 'console-dot';
            const row = document.getElementById(step.id);
            if (row) row.className = 'console-step-row text-neutral-600';
        });
        
        procProgressBar.style.width = '0%';
        procProgressPercent.textContent = '0%';
        procTimeRemaining.textContent = 'Spawning container...';
        
        let currentStepIndex = 0;
        const stepDurations = [300, 400, 400, 600, 400, 300, 200];
        const totalDuration = stepDurations.reduce((a, b) => a + b, 0);
        let elapsed = 0;
        
        function appendLog(type, msg) {
            const line = document.createElement('div');
            line.className = 'flex gap-2.5';
            line.innerHTML = `
                <span class="text-neutral-600 shrink-0 select-none">[${getTimestamp()}]</span>
                <span class="${type === 'success' ? 'text-emerald-400 font-medium' : type === 'info' ? 'text-blue-400' : 'text-neutral-400'}">${msg}</span>
            `;
            consoleTerminal.appendChild(line);
            consoleTerminal.scrollTop = consoleTerminal.scrollHeight;
        }
        
        appendLog('info', 'SPAWNING: Initializing image conversion runtime container...');
        
        function runNextStep() {
            if (currentStepIndex >= stepsData.length) {
                appendLog('info', `FINALIZING: Requesting Laravel ${targetFormat.toUpperCase()} response stream...`);
                
                convertSelectedFileOnServer(appendLog)
                    .then(({ downloadUrl, blob }) => {
                        appendLog('success', `SUCCESS: ${targetFormat.toUpperCase()} compilation finalized. Emitting image download headers.`);
                        showSuccessScreen(downloadUrl, blob);
                    })
                    .catch((error) => {
                        console.error('Laravel conversion error: ', error);
                        appendLog('info', `SERVER CONVERTER ERROR: ${error.message}`);
                        showToast(error.message);
                        setViewState('uploading');
                        btnConvert.removeAttribute('disabled');
                    });
                return;
            }
            
            const step = stepsData[currentStepIndex];
            const dot = document.getElementById(`${step.id}-dot`);
            const row = document.getElementById(step.id);
            
            // Set current step to active
            if (dot) dot.className = 'console-dot active';
            if (row) row.className = 'console-step-row text-neutral-100 font-semibold';
            
            appendLog('info', `${step.label}: ${step.msg}`);
            
            let stepElapsed = 0;
            const duration = stepDurations[currentStepIndex];
            const interval = 40;
            
            const timer = setInterval(() => {
                stepElapsed += interval;
                elapsed += interval;
                
                const percent = Math.min((elapsed / totalDuration) * 100, 99);
                procProgressBar.style.width = `${percent.toFixed(0)}%`;
                procProgressPercent.textContent = `${percent.toFixed(0)}%`;
                
                const remMs = Math.max(totalDuration - elapsed, 0);
                procTimeRemaining.textContent = `${(remMs / 1000).toFixed(1)}s remaining`;
                
                if (stepElapsed >= duration) {
                    clearInterval(timer);
                    
                    // Complete step
                    if (dot) dot.className = 'console-dot complete';
                    if (row) row.className = 'console-step-row text-neutral-400';
                    appendLog('success', `COMPLETED: ${step.label} task finished successfully.`);
                    
                    currentStepIndex++;
                    runNextStep();
                }
            }, interval);
        }
        
        setTimeout(() => {
            runNextStep();
        }, 300);
    }
    
    // -------------------------------------------------------------
    // Success Render & Laravel Download
    // -------------------------------------------------------------
    function showSuccessScreen(customDownloadUrl, customBlob) {
        setViewState('success');
        procProgressBar.style.width = '100%';
        procProgressPercent.textContent = '100%';
        
        const origName = selectedFile ? selectedFile.name : 'image.jpg';
        const outName = origName.substring(0, origName.lastIndexOf('.')) + '.' + targetFormat;
        
        const compSize = customBlob.size;
        const origSize = selectedFile ? selectedFile.size : compSize * 1.3;
        const ratio = Math.max(Math.round((1 - (compSize / origSize)) * 100), 0);
        const seconds = (1.2 + Math.random() * 0.8).toFixed(1);
        
        successFileName.textContent = outName;
        successFileSize.textContent = formatBytes(compSize);
        successTime.textContent = `${seconds}s`;
        successCompression.textContent = `${ratio}% compressed`;
        
        if (successSymbol) {
            successSymbol.textContent = targetFormat.toUpperCase();
        }
        
        btnDownload.href = customDownloadUrl;
        btnDownload.download = outName;
        btnDownload.textContent = 'Download ' + targetFormat.toUpperCase();
        
        btnPreview.onclick = (e) => {
            e.preventDefault();
            const previewWin = window.open(customDownloadUrl, '_blank');
            if (!previewWin) {
                showToast('Popup blocker activated. Please allow popups to preview.');
            }
        };
        
        btnCopyLink.onclick = () => {
            const mockUrl = `https://imagetopng.net/share/${Math.random().toString(36).substring(2, 10)}`;
            navigator.clipboard.writeText(mockUrl).then(() => {
                showToast('Shareable link copied to clipboard!');
            }).catch(() => {
                showToast('Failed to copy share link.');
            });
        };
        
        btnShare.onclick = () => {
            showToast('Sharing menu triggered. Copied link to clip!');
        };
    }
    
    // -------------------------------------------------------------
    // Drag & Drop Handlers
    // -------------------------------------------------------------
    if (dropzone) {
        // Prevent default behaviors for drag events to enable drops
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
            }, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, () => {
                dropzone.classList.add('drag-active');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, () => {
                dropzone.classList.remove('drag-active');
            }, false);
        });

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                handleFileSelected(files[0]);
            }
        }, false);

        dropzone.addEventListener('click', (e) => {
            // Only trigger file picker if no file is currently selected (Idle state)
            if (selectedFile === null) {
                fileInput.click();
            }
        });
    }
    
    if (selectButton) {
        selectButton.addEventListener('click', (e) => {
            e.stopPropagation();
            if (selectedFile === null) {
                fileInput.click();
            }
        });
    }
    
    if (fileInput) {
        fileInput.addEventListener('change', (e) => {
            if (fileInput.files && fileInput.files.length > 0) {
                handleFileSelected(fileInput.files[0]);
            }
        });
    }
    
    // -------------------------------------------------------------
    // Collapse Toggles
    // -------------------------------------------------------------
    if (settingsToggleBtn) {
        settingsToggleBtn.addEventListener('click', () => {
            const isCollapsed = settingsPanel.classList.contains('hidden');
            if (isCollapsed) {
                settingsPanel.classList.remove('hidden');
                settingsToggleIcon.style.transform = 'rotate(180deg)';
                settingsToggleBtn.setAttribute('aria-expanded', 'true');
            } else {
                settingsPanel.classList.add('hidden');
                settingsToggleIcon.style.transform = 'rotate(0deg)';
                settingsToggleBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }
    
    // -------------------------------------------------------------
    // CTA Action Trigger Events
    // -------------------------------------------------------------
    if (btnConvert) {
        btnConvert.addEventListener('click', () => {
            runConversionSimulation();
        });
    }
    
    if (btnCancelUpload) {
        btnCancelUpload.addEventListener('click', () => {
            if (uploadTimer) clearInterval(uploadTimer);
            setViewState('idle');
            showToast('Uploading sequence aborted.');
        });
    }
    
    if (btnReplaceFile) {
        btnReplaceFile.addEventListener('click', () => {
            if (uploadTimer) clearInterval(uploadTimer);
            fileInput.click();
        });
    }
    
    if (btnRetryUpload) {
        btnRetryUpload.addEventListener('click', () => {
            if (selectedFile) handleFileSelected(selectedFile);
        });
    }
    
    if (btnConvertAnother) {
        btnConvertAnother.addEventListener('click', () => {
            setViewState('idle');
        });
    }
});
