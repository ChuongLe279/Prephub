<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Nhập Câu Hỏi TOEIC</title>
    <link href="../styles/questionsStyle.css" rel="stylesheet">
</head>

<body>
    <div class="container-wrapper">
        <div class="form-header">
            <h1>🎓 Nhập Câu Hỏi TOEIC</h1>
            <p>Nhập câu hỏi đơn hoặc nhóm câu hỏi cho đề thi TOEIC</p>
        </div>

        <!-- Message Display -->
        <div id="messageBox" class="message-box"></div>

        <!-- Test Configuration -->
        <div class="test-config">
            <h3 style="margin-top: 0; color: #333;">📋 Cấu Hình Đề Thi & Câu Hỏi</h3>
            <div class="config-row">
                <div class="config-group">
                    <label>Đề Thi <span class="required">*</span></label>
                    <select id="testSelect" onchange="onTestChange()" required>
                        <option value="">-- Chọn đề thi --</option>
                    </select>
                </div>
                <div class="config-group">
                    <label>Phần (Part) <span class="required">*</span></label>
                    <select id="partSelect" onchange="onPartChange()" required>
                        <option value="">-- Chọn part --</option>
                        <option value="1">Part 1: Ảnh</option>
                        <option value="2">Part 2: Câu hỏi ngắn</option>
                        <option value="3">Part 3: Hội thoại</option>
                        <option value="4">Part 4: Độc thoại</option>
                        <option value="5">Part 5: Đọc câu hoàn chỉnh</option>
                        <option value="6">Part 6: Điền từ</option>
                        <option value="7">Part 7: Đọc hiểu</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="partInfo" class="part-info"></div>

        <div class="header-actions">
            <button class="btn btn-add" onclick="addBlock('single')">+ Thêm Câu Đơn</button>
            <button class="btn btn-add-group" onclick="addBlock('group')">+ Thêm Cụm Câu Hỏi</button>
            <button class="btn btn-delete-all" onclick="deleteAllBlocks()">🗑️ Xóa Tất Cả</button>
            <button class="btn btn-submit" onclick="submitData()">💾 Lưu Bài Test</button>
        </div>

        <div id="questions-container"></div>
    </div>

    <template id="single-question-template">
        <div class="question-block single-type" data-type="single">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <div class="badge single block-title">Câu hỏi đơn</div>
                <button class="btn-remove" onclick="removeBlock(this)">✕ Xóa</button>
            </div>

            <!-- Question Number (Auto) -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="font-weight: 600; display: block; margin-bottom: 5px;">Số thứ tự câu hỏi</label>
                    <input type="number" class="question-number form-control" min="1" max="200">
                </div>
                <div style="visibility: hidden;">
                    <label style="font-weight: 600; display: block; margin-bottom: 5px;">Placeholder</label>
                </div>
            </div>

            <div class="media-upload-section">
                <div class="upload-item">
                    <label>📸 Hình ảnh <span class="media-required-badge" style="color: red;"></span></label>
                    <input type="file" accept="image/*" class="image-file" onchange="previewMedia(this, 'image')">
                    <small class="media-hint" style="color: #666;">Tùy chọn</small>
                    <div class="preview-container"></div>
                </div>
                <div class="upload-item">
                    <label>🎧 Âm thanh <span class="media-required-badge" style="color: red;"></span></label>
                    <input type="file" accept="audio/*" class="audio-file" onchange="previewMedia(this, 'audio')">
                    <small class="media-hint" style="color: #666;">Tùy chọn</small>
                    <div class="preview-container"></div>
                </div>
            </div>

            <label><strong>Nội dung câu hỏi:</strong></label>
            <textarea class="form-control question-content" placeholder="Nhập câu hỏi..." onpaste="handleAutoFillPaste(event)"></textarea>

            <div class="options-container">
                <label style="font-weight: 600; display: block; margin-bottom: 10px;">Đáp án <span style="color: red;">*</span></label>
                <div class="option-item"><input type="radio" class="correct-radio" value="A"><span>A.</span><input type="text" class="form-control option-content" placeholder="Đáp án A" required></div>
                <div class="option-item"><input type="radio" class="correct-radio" value="B"><span>B.</span><input type="text" class="form-control option-content" placeholder="Đáp án B" required></div>
                <div class="option-item"><input type="radio" class="correct-radio" value="C"><span>C.</span><input type="text" class="form-control option-content" placeholder="Đáp án C" required></div>
                <div class="option-item"><input type="radio" class="correct-radio" value="D"><span>D.</span><input type="text" class="form-control option-content" placeholder="Đáp án D" required></div>
                <small style="color: #666; display: block; margin-top: 8px;">Chọn đáp án đúng</small>
            </div>

            <label style="font-weight: 600; display: block; margin-top: 15px; margin-bottom: 5px;">Giải thích (Tùy chọn)</label>
            <textarea class="form-control explanation" placeholder="Giải thích đáp án..." rows="2"></textarea>
        </div>
    </template>

    <template id="group-question-template">
        <div class="question-block group-type" data-type="group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <div class="badge group block-title">Cụm câu hỏi</div>
                <button class="btn-remove" onclick="removeBlock(this)">✕ Xóa</button>
            </div>

            <!-- Shared Media Section -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="upload-item">
                    <label>📸 Hình ảnh dùng chung <span class="media-required-badge" style="color: red;"></span></label>
                    <input type="file" accept="image/*" class="group-image-file" onchange="previewMedia(this, 'image')">
                    <small class="media-hint" style="color: #666;">Tùy chọn</small>
                    <div class="preview-container"></div>
                </div>
                <div class="upload-item">
                    <label>🎧 Audio dùng chung <span class="media-required-badge" style="color: red;"></span></label>
                    <input type="file" accept="audio/*" class="group-audio-file" onchange="previewMedia(this, 'audio')">
                    <small class="media-hint" style="color: #666;">Tùy chọn</small>
                    <div class="preview-container"></div>
                </div>
                <div class="upload-item">
                    <label>📝 Đoạn văn (Passages)</label>
                    <textarea class="form-control passage-content" placeholder="Dán đoạn văn dùng chung vào đây..." style="height: 120px;"></textarea>
                </div>
            </div>

            <div class="sub-questions-container"></div>
            
            <button class="btn-add-sub" onclick="addSubQuestionBtn(this)">+ Thêm 1 câu hỏi vào cụm</button>
        </div>
    </template>

    <script>
        // ====== PART CONFIGURATION ======
        const PART_CONFIG = {
            1: { name: 'Ảnh', requiresImage: true, requiresAudio: false, requiresContent: false },
            2: { name: 'Câu hỏi ngắn', requiresImage: false, requiresAudio: true, requiresContent: true },
            3: { name: 'Hội thoại', requiresImage: false, requiresAudio: true, requiresContent: true },
            4: { name: 'Độc thoại', requiresImage: false, requiresAudio: true, requiresContent: true },
            5: { name: 'Đọc câu hoàn chỉnh', requiresImage: false, requiresAudio: false, requiresContent: true },
            6: { name: 'Điền từ', requiresImage: false, requiresAudio: false, requiresContent: true },
            7: { name: 'Đọc hiểu', requiresImage: false, requiresAudio: false, requiresContent: true },
        };

        let globalBlockCounter = 0;
        let loadedQuestionIds = new Set(); // Track loaded question IDs
        let loadedPassageIds = new Set(); // Track loaded passage IDs
        let allTestQuestionNumbers = new Set(); // Track ALL question numbers in this test (all parts)

        // Khởi tạo trang
        document.addEventListener('DOMContentLoaded', () => {
            // Disable part select initially
            document.getElementById('partSelect').disabled = true;
            loadTests();
            addBlock('single');
        });

        // ====== LOAD TESTS ======
        async function loadTests() {
            try {
                // Use relative URL to match current server
                const apiUrl = '/IS207-UIT/server/index.php?path=/api/tests';
                const response = await fetch(apiUrl);
                
                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(`HTTP ${response.status}: ${errorText}`);
                }
                
                const result = await response.json();
                
                if (!result.success) {
                    throw new Error(result.message || 'Không thể tải danh sách đề thi');
                }
                
                if (!result.data || !Array.isArray(result.data)) {
                    throw new Error('Định dạng dữ liệu không hợp lệ');
                }
                
                const testSelect = document.getElementById('testSelect');
                if (result.data.length === 0) {
                    showMessage('Không có đề thi nào', 'warning');
                    return;
                }
                
                result.data.forEach(test => {
                    const option = document.createElement('option');
                    option.value = test.id;
                    option.textContent = test.title || `Đề thi ${test.id}`;
                    testSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading tests - Full details:', {
                    message: error.message,
                    stack: error.stack,
                    type: error.name,
                    url: '/IS207-UIT/server/index.php?path=/api/tests'
                });
                showMessage('⚠️ Lỗi tải danh sách đề thi: ' + error.message, 'error');
            }
        }

        // ====== ON TEST CHANGE ======
        function onTestChange() {
            const testId = document.getElementById('testSelect').value;
            const partSelect = document.getElementById('partSelect');
            
            if (!testId) {
                showMessage('Vui lòng chọn đề thi', 'error');
                // Reset part selection if no test selected
                partSelect.value = '';
                document.getElementById('partInfo').classList.remove('show');
                return;
            }
            
            // Test selected successfully, allow part selection
            partSelect.disabled = false;
            // Show message to guide user to select part
            showMessage('Vui lòng chọn part', 'warning');
        }

        // ====== ON PART CHANGE ======
        function onPartChange() {
            const part = document.getElementById('partSelect').value;
            
            if (!part) {
                document.getElementById('partInfo').classList.remove('show');
                return;
            }

            // Clear the "Vui lòng chọn part" message when user selects a part
            document.getElementById('messageBox').className = 'message-box';
            document.getElementById('messageBox').textContent = '';

            const config = PART_CONFIG[parseInt(part)];
            const partInfo = document.getElementById('partInfo');
            partInfo.innerHTML = `
                <strong>${config.name}</strong>
                Yêu cầu: ${config.requiresImage ? '✓ Hình ảnh' : ''} 
                ${config.requiresAudio ? '✓ Âm thanh' : ''} 
                ${config.requiresContent ? '✓ Nội dung' : ''}
            `;
            partInfo.classList.add('show');

            // Update all media badges based on part
            document.querySelectorAll('.question-block').forEach(block => {
                updateMediaBadges(block, part);
            });

            // Load saved questions for this part into form
            loadSavedQuestionsToForm();
        }

        // ====== LOAD SAVED QUESTIONS TO FORM ======
        async function loadSavedQuestionsToForm() {
            const testId = document.getElementById('testSelect').value;
            const part = document.getElementById('partSelect').value;
            
            console.log('loadSavedQuestionsToForm: testId=', testId, 'part=', part);
            
            if (!testId || !part) {
                console.log('Skipping load: testId or part is empty');
                return;
            }
            
            try {
                // Use relative URL without hardcoding port
                const apiUrl = '/IS207-UIT/server/index.php?path=/api/questions&test_id=' + testId;
                console.log('Fetching from:', apiUrl);
                const response = await fetch(apiUrl);
                
                if (!response.ok) {
                    const text = await response.text();
                    throw new Error(`HTTP ${response.status}: ${text}`);
                }
                
                const result = await response.json();
                console.log('API response:', result);
                
                // Build set of ALL question numbers in this test (for validation)
                allTestQuestionNumbers.clear();
                if (result.success && result.data && Array.isArray(result.data)) {
                    result.data.forEach(q => {
                        if (q.question_number) {
                            allTestQuestionNumbers.add(parseInt(q.question_number));
                        }
                    });
                }
                console.log('All question numbers in test:', Array.from(allTestQuestionNumbers).sort((a, b) => a - b));
                
                if (!result.success || !result.data) {
                    // Không có câu hỏi, xóa form hiện tại
                    console.log('No questions in API response');
                    deleteAllBlocks();
                    addBlock('single');
                    return;
                }
                
                console.log('Total questions from API:', result.data.length);
                
                // Filter questions by part
                const partQuestions = result.data.filter(q => parseInt(q.part) === parseInt(part));
                console.log('Questions for part', part, ':', partQuestions.length, 'filtered from:', result.data.map(q => ({ id: q.id, part: q.part, num: q.question_number })));
                
                if (partQuestions.length === 0) {
                    // Không có câu hỏi cho part này, xóa form hiện tại
                    console.log('No questions for this part, showing empty form');
                    deleteAllBlocks();
                    addBlock('single');
                    return;
                }
                
                // Sort by question_number to maintain insertion order
                partQuestions.sort((a, b) => parseInt(a.question_number) - parseInt(b.question_number));
                
                // Xóa form hiện tại
                deleteAllBlocks();
                
                // Separate single and group questions while maintaining order
                const singleQuestions = partQuestions.filter(q => !q.passage_id);
                const groupQuestions = partQuestions.filter(q => q.passage_id);
                
                // Fetch passages for group questions
                let passagesMap = {};
                if (groupQuestions.length > 0) {
                    const passagesUrl = '/IS207-UIT/server/index.php?path=/api/passages&test_id=' + testId;
                    const passagesResponse = await fetch(passagesUrl);
                    
                    if (!passagesResponse.ok) {
                        const errorText = await passagesResponse.text();
                        console.error('Passages API error:', errorText);
                        showMessage('⚠️ Lỗi tải passages: ' + errorText, 'error');
                        throw new Error(`HTTP ${passagesResponse.status}: ${errorText}`);
                    }
                    
                    const passagesResult = await passagesResponse.json();
                    
                    if (passagesResult.success && passagesResult.data) {
                        passagesResult.data.forEach(p => {
                            passagesMap[p.id] = p;
                        });
                    }
                }
                
                // Process all questions in database order (not separated by type)
                // Build a map of passage -> questions for quick lookup
                const passageToQuestions = {};
                groupQuestions.forEach(q => {
                    if (!passageToQuestions[q.passage_id]) {
                        passageToQuestions[q.passage_id] = [];
                    }
                    passageToQuestions[q.passage_id].push(q);
                });
                
                // Sort sub-questions by question_number
                Object.keys(passageToQuestions).forEach(pid => {
                    passageToQuestions[pid].sort((a, b) => parseInt(a.question_number) - parseInt(b.question_number));
                });
                
                // Track which passages have been processed
                const processedPassages = new Set();
                
                // Load questions in the order they appear in partQuestions
                try {
                    partQuestions.forEach(q => {
                        console.log('Processing question:', q.id, 'passage_id:', q.passage_id, 'question_number:', q.question_number);
                        try {
                            if (q.passage_id) {
                                // This is a group question - add the entire passage block once
                                if (!processedPassages.has(q.passage_id)) {
                                    console.log('Adding group block for passage:', q.passage_id);
                                    processedPassages.add(q.passage_id);
                                    addBlock('group');
                                    const blockDiv = document.querySelector('.question-block.group-type:last-child');
                                    if (blockDiv) {
                                        console.log('Calling loadGroupQuestion with passage:', passagesMap[q.passage_id], 'subQuestions:', passageToQuestions[q.passage_id]);
                                        loadGroupQuestion(passagesMap[q.passage_id], passageToQuestions[q.passage_id], blockDiv);
                                    } else {
                                        console.error('Could not find added group block');
                                    }
                                }
                            } else {
                                // This is a single question
                                console.log('Adding single block for question:', q.id);
                                addBlock('single');
                                const blockDiv = document.querySelector('.question-block.single-type:last-child');
                                if (blockDiv) {
                                    console.log('Calling loadSingleQuestion with question:', q);
                                    loadSingleQuestion(q, blockDiv);
                                } else {
                                    console.error('Could not find added single block');
                                }
                            }
                        } catch (questionError) {
                            console.error('Error processing question', q.id, ':', questionError);
                            showMessage(`⚠️ Lỗi xử lý câu hỏi ${q.id}: ${questionError.message}`, 'warning');
                        }
                    });
                    console.log('Finished loading all questions');
                } catch (blockError) {
                    console.error('Error while processing questions:', blockError);
                    showMessage('⚠️ Lỗi xử lý danh sách câu hỏi: ' + blockError.message, 'warning');
                    throw blockError;
                }
                
            } catch (error) {
                console.error('Error loading saved questions:', error);
                showMessage('⚠️ Lỗi tải câu hỏi đã lưu: ' + error.message, 'warning');
                // Vẫn tạo form trống
                deleteAllBlocks();
                addBlock('single');
            }
        }

        // ====== DISPLAY SAVED QUESTIONS ======
        function displaySavedQuestions(questions) {
            const container = document.getElementById('savedQuestionsContainer');
            const list = document.getElementById('savedQuestionsList');
            
            list.innerHTML = '';
            
            questions.forEach(q => {
                const div = document.createElement('div');
                div.style.cssText = 'padding: 10px; margin-bottom: 10px; background: white; border-radius: 4px; border: 1px solid #ddd;';
                
                const passageLabel = q.passage_id ? '[Cụm]' : '[Đơn]';
                const content = q.content ? q.content.substring(0, 100) + (q.content.length > 100 ? '...' : '') : '(Không có nội dung)';
                const answer = q.correct_answer || '?';
                
                div.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong>Q${q.question_number} ${passageLabel}</strong><br>
                            <small style="color: #666;">${content}</small><br>
                            <small>Đáp án: <strong>${answer}</strong></small>
                        </div>
                        <div>
                            <button onclick="editQuestion(${q.id})" style="padding: 5px 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">Sửa</button>
                            <button onclick="deleteQuestion(${q.id})" style="padding: 5px 10px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Xóa</button>
                        </div>
                    </div>
                `;
                
                list.appendChild(div);
            });
            
            container.style.display = 'block';
        }

        // ====== EDIT QUESTION ======
        // ====== LOAD SINGLE QUESTION ======
        function loadSingleQuestion(question, block) {
            if (!block) return;
            
            // Set question number
            const numberInput = block.querySelector('.question-number');
            if (numberInput) numberInput.value = question.question_number;
            
            // Set content
            const contentInput = block.querySelector('.question-content');
            if (contentInput) contentInput.value = question.content || '';
            
            // Set options
            const optionInputs = block.querySelectorAll('.options-container .option-content');
            if (question.options && question.options.length === 4) {
                question.options.forEach((opt, idx) => {
                    if (optionInputs[idx]) {
                        optionInputs[idx].value = opt.content || '';
                    }
                });
            }
            
            // Set correct answer
            const correctRadios = block.querySelectorAll('.correct-radio');
            correctRadios.forEach(radio => {
                if (radio.value === question.correct_answer) {
                    radio.checked = true;
                }
            });
            
            // Set explanation
            const explanationInput = block.querySelector('.explanation');
            if (explanationInput) explanationInput.value = question.explanation || '';
            
            // Load media (image and audio)
            const mediaSection = block.querySelector('.media-upload-section');
            if (mediaSection) {
                // Load image
                if (question.image_url) {
                    const imageInput = mediaSection.querySelector('.upload-item:nth-child(1) input[type="file"]');
                    if (imageInput) {
                        // Mark that media already exists - validation will know not to require new file
                        imageInput.dataset.existingUrl = question.image_url;
                    }
                    const imagePreview = mediaSection.querySelector('.upload-item:nth-child(1) .preview-container');
                    if (imagePreview) {
                        imagePreview.innerHTML = `<img src="${question.image_url}" alt="Question image" style="max-width: 200px;">`;
                    }
                }
                
                // Load audio
                if (question.audio_url) {
                    const audioInput = mediaSection.querySelector('.upload-item:nth-child(2) input[type="file"]');
                    if (audioInput) {
                        // Mark that media already exists - validation will know not to require new file
                        audioInput.dataset.existingUrl = question.audio_url;
                    }
                    const audioPreview = mediaSection.querySelector('.upload-item:nth-child(2) .preview-container');
                    if (audioPreview) {
                        audioPreview.innerHTML = `<audio controls src="${question.audio_url}" style="width: 100%;"></audio>`;
                    }
                }
            }
            
            // Store question ID for tracking
            block.dataset.questionId = question.id;
            loadedQuestionIds.add(question.id);
        }

        // ====== LOAD GROUP QUESTION ======
        function loadGroupQuestion(passage, subQuestions, block) {
            if (!block) return;
            
            // Set passage content
            const passageInput = block.querySelector('.passage-content');
            if (passageInput) passageInput.value = passage.content || '';
            
            // Load passage media - find preview containers for audio and image
            const audioUploadItem = block.querySelector('.group-audio-file')?.closest('.upload-item');
            const imageUploadItem = block.querySelector('.group-image-file')?.closest('.upload-item');
            
            // Load passage image
            if (passage.image_url && imageUploadItem) {
                const imageInput = block.querySelector('.group-image-file');
                if (imageInput) {
                    // Mark that media already exists
                    imageInput.dataset.existingUrl = passage.image_url;
                }
                const imagePreview = imageUploadItem.querySelector('.preview-container');
                if (imagePreview) {
                    imagePreview.innerHTML = `<img src="${passage.image_url}" alt="Passage image" style="max-width: 200px;">`;
                }
            }
            
            // Load passage audio
            if (passage.audio_url && audioUploadItem) {
                const audioInput = block.querySelector('.group-audio-file');
                if (audioInput) {
                    // Mark that media already exists
                    audioInput.dataset.existingUrl = passage.audio_url;
                }
                const audioPreview = audioUploadItem.querySelector('.preview-container');
                if (audioPreview) {
                    audioPreview.innerHTML = `<audio controls src="${passage.audio_url}" style="width: 100%;"></audio>`;
                }
            }
            
            // Store passage ID for reference
            block.dataset.passageId = passage.id;
            loadedPassageIds.add(passage.id);
            
            // Remove default sub-questions
            const subContainer = block.querySelector('.sub-questions-container');
            const defaultSubs = subContainer.querySelectorAll('.sub-question-item');
            defaultSubs.forEach(sub => sub.remove());
            
            // Load sub-questions
            if (subQuestions && subQuestions.length > 0) {
                subQuestions.forEach(subQ => {
                    const subDiv = createSubQuestionDOM(block.dataset.blockId, subQ.question_number);
                    
                    // Fill sub-question data
                    subDiv.querySelector('.sub-question-number').value = subQ.question_number;
                    subDiv.querySelector('.question-content').value = subQ.content || '';
                    
                    // Fill options
                    const optionInputs = subDiv.querySelectorAll('.sub-options-grid .option-content');
                    if (subQ.options && subQ.options.length === 4) {
                        subQ.options.forEach((opt, idx) => {
                            if (optionInputs[idx]) {
                                optionInputs[idx].value = opt.content || '';
                            }
                        });
                    }
                    
                    // Set correct answer
                    const radios = subDiv.querySelectorAll('input[type="radio"]');
                    radios.forEach(radio => {
                        if (radio.value === subQ.correct_answer) {
                            radio.checked = true;
                        }
                    });
                    
                    // Set explanation
                    const explanationInput = subDiv.querySelector('.explanation');
                    if (explanationInput) explanationInput.value = subQ.explanation || '';
                    
                    // Store question ID for tracking
                    subDiv.dataset.questionId = subQ.id;
                    loadedQuestionIds.add(subQ.id);
                    
                    subContainer.appendChild(subDiv);
                });
            }
        }

        // ====== UPDATE MEDIA BADGES ======
        function updateMediaBadges(block, part) {
            const config = PART_CONFIG[parseInt(part)];
            const audioLabels = block.querySelectorAll('.upload-item:nth-child(2) .media-required-badge');
            const imageLabels = block.querySelectorAll('.upload-item:nth-child(1) .media-required-badge');
            const audioHints = block.querySelectorAll('.upload-item:nth-child(2) .media-hint');
            const imageHints = block.querySelectorAll('.upload-item:nth-child(1) .media-hint');

            audioLabels.forEach(label => {
                label.textContent = config.requiresAudio ? '(Bắt buộc)' : '';
            });
            imageLabels.forEach(label => {
                label.textContent = config.requiresImage ? '(Bắt buộc)' : '';
            });
            audioHints.forEach(hint => {
                hint.textContent = config.requiresAudio ? 'MP3, WAV, OGG - tối đa 50MB' : 'Tùy chọn';
            });
            imageHints.forEach(hint => {
                hint.textContent = config.requiresImage ? 'JPG, PNG, GIF - tối đa 5MB' : 'Tùy chọn';
            });
        }

        // ====== UPDATE QUESTION COUNT ======
        function updateQuestionCount() {
            const singleQCount = document.querySelectorAll('.single-type').length;
            const subQCount = Array.from(document.querySelectorAll('.group-type')).reduce((sum, group) => {
                return sum + group.querySelectorAll('.sub-question-item').length;
            }, 0);
            
            const total = singleQCount + subQCount;
            const countElement = document.getElementById('questionCount');
            if (countElement) {
                countElement.textContent = total;
            }
        }

        // ====== ADD BLOCK (ENHANCED) ======
        function addBlock(type) {
            const testId = document.getElementById('testSelect').value;
            const part = document.getElementById('partSelect').value;
            
            console.log('addBlock called with type:', type, 'testId:', testId, 'part:', part);
            
            if (!testId) {
                showMessage('Vui lòng chọn đề thi trước', 'error');
                return;
            }
            
            if (!part) {
                showMessage('Vui lòng chọn part trước', 'error');
                return;
            }

            globalBlockCounter++;
            const container = document.getElementById('questions-container');
            const templateId = type === 'single' ? 'single-question-template' : 'group-question-template';
            const clone = document.getElementById(templateId).content.cloneNode(true);
            
            const blockDiv = clone.querySelector('.question-block');
            blockDiv.dataset.blockId = globalBlockCounter;
            console.log('Created', type, 'block with ID:', globalBlockCounter);

            // Calculate question number (last number + 1, or 1 if first)
            const nextNumber = getLastQuestionNumber() + 1;

            if (type === 'single') {
                const questionNumberInput = blockDiv.querySelector('.question-number');
                if (questionNumberInput) {
                    questionNumberInput.value = nextNumber;
                }
                const radios = blockDiv.querySelectorAll('.correct-radio');
                radios.forEach(radio => radio.name = `correct_block_${globalBlockCounter}`);
            } else if (type === 'group') {
                const subContainer = blockDiv.querySelector('.sub-questions-container');
                // Start numbering from nextNumber for group questions
                for (let i = 0; i < 3; i++) {
                    const subQuestion = createSubQuestionDOM(globalBlockCounter, nextNumber + i);
                    subContainer.appendChild(subQuestion);
                }
            }

            container.appendChild(clone);
            
            const blocksCount = document.querySelectorAll('.question-block').length;
            console.log('After adding block, total blocks in DOM:', blocksCount);
            
            // Update media badges for current part
            const config = PART_CONFIG[parseInt(part)];
            updateMediaBadges(blockDiv, part);

            updateQuestionCount();
        }

        // ====== CREATE SUB QUESTION DOM ======
        function createSubQuestionDOM(blockId, questionNumber = null) {
            const subId = Date.now() + Math.floor(Math.random() * 1000);
            const radioName = `correct_group_${blockId}_sub_${subId}`;
            
            const div = document.createElement('div');
            div.className = 'sub-question-item';
            div.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <button class="btn-remove-sub" onclick="removeSubQuestion(this)">Xóa</button>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div>
                        <label style="font-weight: 600; display: block; margin-bottom: 5px;">Số thứ tự câu hỏi</label>
                        <input type="number" class="sub-question-number form-control" min="1" max="200" value="${questionNumber || 1}">
                    </div>
                    <div style="visibility: hidden;">
                        <label style="font-weight: 600; display: block; margin-bottom: 5px;">Placeholder</label>
                    </div>
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="font-weight: 600; display: block; margin-bottom: 5px;">Nội dung câu hỏi</label>
                    <textarea class="form-control question-content" placeholder="Nhập câu hỏi..." rows="2" onpaste="handleAutoFillPaste(event)"></textarea>
                </div>
                <div style="margin-top: 10px; margin-bottom: 5px; font-weight: 600;">Đáp án <span style="color: red;">*</span></div>
                <div class="sub-options-grid">
                    <div class="sub-option"><input type="radio" name="${radioName}" value="A" required><span>A.</span><input type="text" class="form-control option-content" placeholder="Đáp án A" required></div>
                    <div class="sub-option"><input type="radio" name="${radioName}" value="B" required><span>B.</span><input type="text" class="form-control option-content" placeholder="Đáp án B" required></div>
                    <div class="sub-option"><input type="radio" name="${radioName}" value="C" required><span>C.</span><input type="text" class="form-control option-content" placeholder="Đáp án C" required></div>
                    <div class="sub-option"><input type="radio" name="${radioName}" value="D" required><span>D.</span><input type="text" class="form-control option-content" placeholder="Đáp án D" required></div>
                </div>
                <label style="font-weight: 600; display: block; margin-top: 15px; margin-bottom: 5px;">Giải thích (Tùy chọn)</label>
                <textarea class="form-control explanation" placeholder="Giải thích đáp án..." rows="2"></textarea>
            `;
            return div;
        }

        // ====== ADD SUB QUESTION BUTTON ======
        function addSubQuestionBtn(button) {
            const blockDiv = button.closest('.question-block');
            const blockId = blockDiv.dataset.blockId;
            const subContainer = blockDiv.querySelector('.sub-questions-container');
            const nextNumber = getLastQuestionNumber() + 1;
            const newSubQuestion = createSubQuestionDOM(blockId, nextNumber);
            subContainer.appendChild(newSubQuestion);
            
            updateQuestionCount();
        }

        // ====== RECALCULATE ALL QUESTION NUMBERS (DEPRECATED - kept for reference) ======
        // Note: Question numbers should be manually controlled by user, not auto-calculated
        // This function is kept but not called automatically
        function recalculateAllQuestionNumbers() {
            const blocks = document.querySelectorAll('.question-block');
            let currentNumber = 1;
            
            blocks.forEach(block => {
                if (block.dataset.type === 'single') {
                    const input = block.querySelector('.question-number');
                    if (input) {
                        input.value = currentNumber;
                        currentNumber++;
                    }
                } else if (block.dataset.type === 'group') {
                    const subQuestions = block.querySelectorAll('.sub-question-item');
                    subQuestions.forEach(subQ => {
                        const input = subQ.querySelector('.sub-question-number');
                        if (input) {
                            input.value = currentNumber;
                            currentNumber++;
                        }
                    });
                }
            });
        }

        // ====== REMOVE SUB QUESTION ======
        function removeSubQuestion(button) {
            // Get block reference BEFORE removing the sub-question
            const subQuestion = button.closest('.sub-question-item');
            const block = subQuestion.closest('.question-block');
            
            // Remove the sub-question
            subQuestion.remove();
            
            // Check if there are any sub-questions left in this block
            const remainingSubQuestions = block.querySelectorAll('.sub-question-item');
            
            // If no sub-questions left, remove the entire block
            if (remainingSubQuestions.length === 0) {
                console.log('No sub-questions left, removing block');
                block.remove();
            }
            
            updateQuestionCount();
        }

        // ====== UPDATE SUB QUESTION NUMBERS AFTER DELETE ======
        function updateSubQuestionNumbersAfterDelete(deleteButton) {
            // Get the parent block
            const block = deleteButton.closest('.question-block');
            
            // Check if there are any sub-questions left
            const remainingSubQuestions = block.querySelectorAll('.sub-question-item');
            
            // If no sub-questions left, remove the entire block
            if (remainingSubQuestions.length === 0) {
                console.log('No sub-questions left, removing block');
                block.remove();
            }
        }

        // ====== REMOVE BLOCK ======
        function removeBlock(button) {
            const blockDiv = button.closest('.question-block');
            blockDiv.remove();
            
            updateQuestionCount();
        }

        // ====== DELETE ALL ======
        function deleteAllBlocks() {
            const container = document.getElementById('questions-container');
            if (container) {
                container.innerHTML = '';
            }
            globalBlockCounter = 0;
            updateQuestionCount();
        }

        // ====== GET LAST QUESTION NUMBER ======
        function getLastQuestionNumber() {
            const singleQuestions = document.querySelectorAll('.single-type .question-number');
            const subQuestions = document.querySelectorAll('.sub-question-item .sub-question-number');
            const allQuestionInputs = [...singleQuestions, ...subQuestions];
            
            if (allQuestionInputs.length === 0) return 0;
            
            let maxNumber = 0;
            allQuestionInputs.forEach(input => {
                const num = parseInt(input.value) || 0;
                if (num > maxNumber) maxNumber = num;
            });
            
            return maxNumber;
        }

        // ====== PREVIEW MEDIA ======
        function previewMedia(input, type) {
            // Find the preview container - it could be a sibling or in the same upload-item
            let container = input.nextElementSibling;
            // Skip over elements like <small> to find preview-container
            while (container && !container.classList.contains('preview-container')) {
                container = container.nextElementSibling;
            }
            
            if (!container) {
                // Fallback: find within parent upload-item
                const uploadItem = input.closest('.upload-item');
                if (uploadItem) {
                    container = uploadItem.querySelector('.preview-container');
                }
            }
            
            if (!container) return;
            
            container.innerHTML = '';
            
            if (!input.files || !input.files[0]) return;

            const file = input.files[0];
            const url = URL.createObjectURL(file);
            
            // Validate file size
            const maxSize = type === 'audio' ? 50 * 1024 * 1024 : 5 * 1024 * 1024;
            if (file.size > maxSize) {
                showMessage(`File quá lớn! Tối đa ${type === 'audio' ? '50MB' : '5MB'}`, 'error');
                input.value = '';
                return;
            }

            const isAudio = type === 'audio' || (type === 'auto' && file.type.startsWith('audio'));
            const isImage = type === 'image' || (type === 'auto' && file.type.startsWith('image'));

            if (isImage) {
                const img = document.createElement('img');
                img.src = url;
                img.style.maxWidth = '200px';
                container.appendChild(img);
            } else if (isAudio) {
                const audio = document.createElement('audio');
                audio.controls = true;
                audio.src = url;
                audio.style.width = '100%';
                container.appendChild(audio);
            }
        }

        // ====== AUTO FILL PASTE ======
        function handleAutoFillPaste(e) {
            const pasteText = (e.clipboardData || window.clipboardData).getData('text').trim();
            const lines = pasteText.split('\n').map(line => line.trim()).filter(line => line.length > 0);

            if (lines.length >= 5) {
                e.preventDefault();
                
                const targetBlock = e.target.closest('.single-type') || e.target.closest('.sub-question-item');
                const optionInputs = targetBlock.querySelectorAll('.option-content');

                const options = lines.slice(-4);
                const questionText = lines.slice(0, lines.length - 4).join('\n');

                e.target.value = questionText;

                for (let i = 0; i < 4; i++) {
                    if (optionInputs[i]) {
                        const cleanContent = options[i].replace(/^[A-Da-d1-4][\.\)\s\-\/\]]+/, '').trim();
                        optionInputs[i].value = cleanContent;
                    }
                }
            }
        }

        // ====== SHOW MESSAGE ======
        function showMessage(message, type) {
            const messageBox = document.getElementById('messageBox');
            messageBox.textContent = message;
            messageBox.className = `message-box ${type}`;

            // Auto-disappear only for success messages
            if (type === 'success') {
                setTimeout(() => {
                    messageBox.className = 'message-box';
                }, 5000);
            }
        }

        // ====== SUBMIT DATA ======
        async function submitData() {
            const testId = document.getElementById('testSelect').value;
            const part = document.getElementById('partSelect').value;

            if (!testId) {
                showMessage('Vui lòng chọn đề thi', 'error');
                return;
            }

            if (!part) {
                showMessage('Vui lòng chọn part', 'error');
                return;
            }

            const blocks = document.querySelectorAll('.question-block');
            if (blocks.length === 0 && loadedQuestionIds.size === 0 && loadedPassageIds.size === 0) {
                showMessage('Vui lòng thêm ít nhất 1 câu hỏi', 'error');
                return;
            }

            // Validate all questions
            let hasError = false;
            const seenQuestionNumbers = new Set();
            
            blocks.forEach((block, blockIndex) => {
                if (block.dataset.type === 'single') {
                    // Validate single question
                    const questionNumber = block.querySelector('.question-number')?.value.trim();
                    if (!questionNumber) {
                        showMessage(`Câu #${blockIndex + 1}: Vui lòng nhập số thứ tự câu hỏi`, 'error');
                        hasError = true;
                    } else {
                        const qNum = parseInt(questionNumber);
                        // Check for duplicate within current form
                        if (seenQuestionNumbers.has(qNum)) {
                            showMessage(`❌ Câu #${blockIndex + 1}: Số thứ tự ${questionNumber} bị trùng lặp trong form hiện tại`, 'error');
                            hasError = true;
                        } 
                        // Check for duplicate in entire test (other parts)
                        else if (allTestQuestionNumbers.has(qNum) && !block.dataset.questionId) {
                            showMessage(`❌ Câu #${blockIndex + 1}: Số thứ tự ${questionNumber} đã tồn tại ở phần khác của đề thi này`, 'error');
                            hasError = true;
                        }
                        else {
                            seenQuestionNumbers.add(qNum);
                        }
                        // Check range
                        if (qNum < 1 || qNum > 200) {
                            showMessage(`Câu #${blockIndex + 1}: Số thứ tự phải nằm trong khoảng 1-200`, 'error');
                            hasError = true;
                        }
                    }

                    const content = block.querySelector('.question-content')?.value.trim();
                    if (!content) {
                        showMessage(`Câu #${blockIndex + 1}: Vui lòng nhập nội dung câu hỏi`, 'error');
                        hasError = true;
                    }

                    const options = block.querySelectorAll('.option-content');
                    options.forEach((opt, i) => {
                        if (!opt.value.trim()) {
                            showMessage(`Câu #${blockIndex + 1}: Vui lòng nhập đầy đủ 4 đáp án`, 'error');
                            hasError = true;
                        }
                    });

                    if (!block.querySelector('.correct-radio:checked')) {
                        showMessage(`Câu #${blockIndex + 1}: Vui lòng chọn đáp án đúng`, 'error');
                        hasError = true;
                    }

                    // Validate media requirements for part
                    const audioInput = block.querySelector('.audio-file');
                    const imageInput = block.querySelector('.image-file');
                    const audioFile = audioInput?.files[0];
                    const imageFile = imageInput?.files[0];
                    const hasExistingAudio = audioInput?.dataset.existingUrl;
                    const hasExistingImage = imageInput?.dataset.existingUrl;

                    if (part === '1' && !audioFile && !imageFile && !hasExistingAudio && !hasExistingImage) {
                        showMessage(`Câu #${blockIndex + 1}: Part 1 cần ít nhất hình ảnh hoặc âm thanh`, 'error');
                        hasError = true;
                    } else if (['2', '3', '4'].includes(part) && !audioFile && !hasExistingAudio) {
                        showMessage(`Câu #${blockIndex + 1}: Part ${part} cần âm thanh`, 'error');
                        hasError = true;
                    }
                } else {
                    // Validate group question
                    const passageContent = block.querySelector('.passage-content')?.value.trim();
                    const audioInput = block.querySelector('.group-audio-file');
                    const imageInput = block.querySelector('.group-image-file');
                    const audioFile = audioInput?.files[0];
                    const imageFile = imageInput?.files[0];
                    const hasExistingAudio = audioInput?.dataset.existingUrl;
                    const hasExistingImage = imageInput?.dataset.existingUrl;

                    // Validate passage media requirements
                    if (part === '1' && !audioFile && !imageFile && !hasExistingAudio && !hasExistingImage) {
                        showMessage(`Cụm #${blockIndex + 1}: Part 1 cần ít nhất hình ảnh hoặc âm thanh`, 'error');
                        hasError = true;
                    } else if (['2', '3', '4'].includes(part) && !audioFile && !hasExistingAudio) {
                        showMessage(`Cụm #${blockIndex + 1}: Part ${part} cần âm thanh`, 'error');
                        hasError = true;
                    }

                    const subQuestions = block.querySelectorAll('.sub-question-item');
                    if (subQuestions.length === 0) {
                        showMessage(`Cụm #${blockIndex + 1}: Vui lòng thêm ít nhất 1 câu hỏi`, 'error');
                        hasError = true;
                        return;
                    }

                    subQuestions.forEach((subQ, subIndex) => {
                        // Validate question number
                        const qNumber = subQ.querySelector('.sub-question-number')?.value.trim();
                        if (!qNumber) {
                            showMessage(`Cụm #${blockIndex + 1}, Câu #${subIndex + 1}: Vui lòng nhập số thứ tự câu hỏi`, 'error');
                            hasError = true;
                        } else {
                            const qNum = parseInt(qNumber);
                            // Check for duplicate within current form
                            if (seenQuestionNumbers.has(qNum)) {
                                showMessage(`❌ Cụm #${blockIndex + 1}, Câu #${subIndex + 1}: Số thứ tự ${qNumber} bị trùng lặp trong form hiện tại`, 'error');
                                hasError = true;
                            }
                            // Check for duplicate in entire test (other parts)
                            else if (allTestQuestionNumbers.has(qNum) && !subQ.dataset.questionId) {
                                showMessage(`❌ Cụm #${blockIndex + 1}, Câu #${subIndex + 1}: Số thứ tự ${qNumber} đã tồn tại ở phần khác của đề thi này`, 'error');
                                hasError = true;
                            }
                            else {
                                seenQuestionNumbers.add(qNum);
                            }
                            // Check range
                            if (qNum < 1 || qNum > 200) {
                                showMessage(`Cụm #${blockIndex + 1}, Câu #${subIndex + 1}: Số thứ tự phải nằm trong khoảng 1-200`, 'error');
                                hasError = true;
                            }
                        }

                        // Validate content
                        const qContent = subQ.querySelector('.question-content')?.value.trim();
                        if (!qContent) {
                            showMessage(`Cụm #${blockIndex + 1}, Câu #${subIndex + 1}: Vui lòng nhập nội dung câu hỏi`, 'error');
                            hasError = true;
                        }

                        // Validate options
                        const options = subQ.querySelectorAll('.option-content');
                        options.forEach(opt => {
                            if (!opt.value.trim()) {
                                showMessage(`Cụm #${blockIndex + 1}, Câu #${subIndex + 1}: Vui lòng nhập đầy đủ 4 đáp án`, 'error');
                                hasError = true;
                            }
                        });

                        // Validate correct answer
                        if (!subQ.querySelector('input[type="radio"]:checked')) {
                            showMessage(`Cụm #${blockIndex + 1}, Câu #${subIndex + 1}: Vui lòng chọn đáp án đúng`, 'error');
                            hasError = true;
                        }
                    });
                }
            });

            if (hasError) return;

            // Collect currently remaining question IDs (after deletions)
            const currentQuestionIds = new Set();
            const currentPassageIds = new Set();
            blocks.forEach(block => {
                if (block.dataset.questionId) {
                    currentQuestionIds.add(parseInt(block.dataset.questionId));
                }
                if (block.dataset.passageId) {
                    currentPassageIds.add(parseInt(block.dataset.passageId));
                }
                // Also track sub-question IDs for group blocks
                const subQuestions = block.querySelectorAll('.sub-question-item');
                subQuestions.forEach(sub => {
                    if (sub.dataset.questionId) {
                        currentQuestionIds.add(parseInt(sub.dataset.questionId));
                    }
                });
            });

            // Find deleted questions and passages
            const deletedQuestionIds = Array.from(loadedQuestionIds).filter(id => !currentQuestionIds.has(id));
            const deletedPassageIds = Array.from(loadedPassageIds).filter(id => !currentPassageIds.has(id));
            console.log('Deleted questions:', deletedQuestionIds, 'Deleted passages:', deletedPassageIds);

            // Show loading state
            const submitBtn = event?.target || document.querySelector('.btn-submit');
            const originalText = submitBtn?.textContent;
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = '⏳ Đang lưu...';
            }

            try {
                // Delete questions that were removed
                for (const qId of deletedQuestionIds) {
                    try {
                        const deleteResponse = await fetch('/IS207-UIT/server/index.php?path=/api/questions/' + qId, {
                            method: 'DELETE'
                        });
                        if (deleteResponse.ok) {
                            console.log('Deleted question:', qId);
                        } else {
                            console.warn('Failed to delete question:', qId, deleteResponse.status);
                            showMessage(`⚠️ Không thể xóa câu hỏi ${qId} (mã: ${deleteResponse.status})`, 'warning');
                        }
                    } catch (deleteError) {
                        console.error('Error deleting question', qId, ':', deleteError);
                        showMessage(`⚠️ Lỗi xóa câu hỏi ${qId}: ${deleteError.message}`, 'warning');
                    }
                }

                // Delete passages that were removed
                for (const pId of deletedPassageIds) {
                    try {
                        const deleteResponse = await fetch('/IS207-UIT/server/index.php?path=/api/passages/' + pId, {
                            method: 'DELETE'
                        });
                        if (deleteResponse.ok) {
                            console.log('Deleted passage:', pId);
                        } else {
                            console.warn('Failed to delete passage:', pId, deleteResponse.status);
                            showMessage(`⚠️ Không thể xóa cụm câu ${pId} (mã: ${deleteResponse.status})`, 'warning');
                        }
                    } catch (deleteError) {
                        console.error('Error deleting passage', pId, ':', deleteError);
                        showMessage(`⚠️ Lỗi xóa cụm câu ${pId}: ${deleteError.message}`, 'warning');
                    }
                }

                // Process each block and submit
                let totalCreated = 0;
                let totalErrors = 0;

                for (let blockIndex = 0; blockIndex < blocks.length; blockIndex++) {
                    const block = blocks[blockIndex];

                    if (block.dataset.type === 'single') {
                        const success = await submitSingleQuestion(block, testId, part);
                        if (success) totalCreated++;
                        else totalErrors++;
                    } else {
                        // Group question - need to create passage first if has content
                        const result = await submitGroupQuestions(block, testId, part);
                        totalCreated += result.created;
                        totalErrors += result.errors;
                    }
                }

                if (totalErrors === 0) {
                    const deleteMsg = deletedQuestionIds.length > 0 || deletedPassageIds.length > 0 
                        ? ` (xóa ${deletedQuestionIds.length + deletedPassageIds.length} câu/cụm)` 
                        : '';
                    showMessage(`✅ Thành công! Đã lưu ${totalCreated} câu hỏi${deleteMsg}`, 'success');
                    // Reset form but keep part to reload questions
                    const savedPart = part; // Save part before reset
                    deleteAllBlocks();
                    loadedQuestionIds.clear(); // Clear tracking
                    loadedPassageIds.clear();
                    document.getElementById('testSelect').value = testId; // Keep testId
                    document.getElementById('partSelect').value = savedPart; // Keep part
                    document.getElementById('partInfo').classList.remove('show');
                    // Reload form to show newly added questions with delay
                    console.log('Scheduled reload in 800ms for part:', savedPart);
                    setTimeout(() => {
                        console.log('Triggering loadSavedQuestionsToForm with part:', document.getElementById('partSelect').value);
                        loadSavedQuestionsToForm();
                    }, 800);
                } else {
                    showMessage(`⚠️ Lưu ${totalCreated} câu hỏi thành công, ${totalErrors} lỗi`, 'warning');
                }
            } catch (error) {
                console.error('Error submitting data:', error);
                showMessage('❌ Lỗi lưu dữ liệu: ' + error.message, 'error');
            } finally {
                // Restore button state
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText || '💾 Lưu Bài Test';
                }
            }
        }

        // ====== SUBMIT SINGLE QUESTION ======
        async function submitSingleQuestion(block, testId, part) {
            try {
                const questionNumber = block.querySelector('.question-number').value;
                const content = block.querySelector('.question-content').value.trim() || null;
                const correctAnswer = block.querySelector('.correct-radio:checked')?.value;
                const explanation = block.querySelector('.explanation').value.trim() || null;

                // Get options
                const optionElements = block.querySelectorAll('.options-container .option-item .option-content');
                const options = {
                    A: optionElements[0]?.value.trim() || '',
                    B: optionElements[1]?.value.trim() || '',
                    C: optionElements[2]?.value.trim() || '',
                    D: optionElements[3]?.value.trim() || ''
                };

                // Validate options
                Object.values(options).forEach(opt => {
                    if (!opt) throw new Error('Thiếu đáp án');
                });

                // Check if editing existing question
                const questionId = block.dataset.questionId;
                if (questionId) {
                    // Delete old question first to avoid duplicate
                    await fetch('/IS207-UIT/server/index.php?path=/api/questions/' + questionId, {
                        method: 'DELETE'
                    });
                }

                // Create FormData
                const formData = new FormData();
                formData.append('test_id', testId);
                formData.append('part', part);
                formData.append('question_number', questionNumber);
                formData.append('content', content);
                formData.append('correct_answer', correctAnswer);
                formData.append('explanation', explanation);
                formData.append('options', JSON.stringify(options));

                // Add files if present
                const audioInput = block.querySelector('.audio-file');
                const audioFile = audioInput?.files[0];
                if (audioFile) {
                    formData.append('audio_file', audioFile);
                } else if (audioInput?.dataset.existingUrl) {
                    // Keep existing audio URL
                    formData.append('audio_url', audioInput.dataset.existingUrl);
                }

                const imageInput = block.querySelector('.image-file');
                const imageFile = imageInput?.files[0];
                if (imageFile) {
                    formData.append('image_file', imageFile);
                } else if (imageInput?.dataset.existingUrl) {
                    // Keep existing image URL
                    formData.append('image_url', imageInput.dataset.existingUrl);
                }

                // Send to API
                const response = await fetch('/IS207-UIT/server/index.php?path=/api/questions', {
                    method: 'POST',
                    body: formData
                });

                let result;
                try {
                    result = await response.json();
                } catch (parseError) {
                    const text = await response.text();
                    console.error('Response parse error:', {
                        status: response.status,
                        text: text,
                        parseError: parseError.message
                    });
                    showMessage(`❌ Phản hồi server không hợp lệ: ${text.substring(0, 100)}...`, 'error');
                    throw new Error(`Server response invalid: ${text}`);
                }

                console.log('API Response:', {
                    status: response.status,
                    success: result.success,
                    message: result.message,
                    fullResult: result
                });

                if (!result.success) {
                    console.error('API Error Response:', {
                        status: response.status,
                        result: result
                    });
                    showMessage(`❌ Câu ${questionNumber}: ${result.message || 'Lỗi không xác định'}`, 'error');
                    return false;
                }

                return true;
            } catch (error) {
                showMessage(`❌ Lỗi lưu câu hỏi: ${error.message}`, 'error');
                return false;
            }
        }

        // ====== SUBMIT GROUP QUESTIONS ======
        async function submitGroupQuestions(block, testId, part) {
            let created = 0;
            let errors = 0;

            try {
                const passageContent = block.querySelector('.passage-content').value.trim() || null;
                const subQuestions = block.querySelectorAll('.sub-question-item');

                // Create passage if has content OR has sub-questions
                let passageId = null;
                if (passageContent || subQuestions.length > 0) {
                    try {
                        // Check if editing existing passage - delete old first
                        const existingPassageId = block.dataset.passageId;
                        if (existingPassageId) {
                            await fetch('/IS207-UIT/server/index.php?path=/api/passages/' + existingPassageId, {
                                method: 'DELETE'
                            });
                            console.log('Deleted old passage:', existingPassageId);
                        }

                        const passageFormData = new FormData();
                        passageFormData.append('test_id', testId);
                        passageFormData.append('part', part);
                        if (passageContent) {
                            passageFormData.append('content', passageContent);
                        }

                        // Handle audio file
                        const audioFile = block.querySelector('.group-audio-file')?.files[0];
                        if (audioFile) {
                            passageFormData.append('audio_file', audioFile);
                            console.log('Added audio file:', audioFile.name);
                        } else {
                            // If no new file but has existing audio, include existing URL
                            const audioInput = block.querySelector('.group-audio-file');
                            if (audioInput?.dataset.existingUrl) {
                                passageFormData.append('audio_url', audioInput.dataset.existingUrl);
                                console.log('Keeping existing audio:', audioInput.dataset.existingUrl);
                            }
                        }

                        // Handle image file
                        const imageFile = block.querySelector('.group-image-file')?.files[0];
                        if (imageFile) {
                            passageFormData.append('image_file', imageFile);
                            console.log('Added image file:', imageFile.name);
                        } else {
                            // If no new file but has existing image, include existing URL
                            const imageInput = block.querySelector('.group-image-file');
                            if (imageInput?.dataset.existingUrl) {
                                passageFormData.append('image_url', imageInput.dataset.existingUrl);
                                console.log('Keeping existing image:', imageInput.dataset.existingUrl);
                            }
                        }

                        console.log('Passage FormData ready, audio:', audioFile?.name || 'none', 'image:', imageFile?.name || 'none');

                        const passageResponse = await fetch('/IS207-UIT/server/index.php?path=/api/passages', {
                            method: 'POST',
                            body: passageFormData
                        });

                        const passageResult = await passageResponse.json();

                        console.log('Passage API Response:', {
                            status: passageResponse.status,
                            success: passageResult.success,
                            message: passageResult.message,
                            data: passageResult.data,
                            fullResult: passageResult
                        });

                        if (passageResult.success) {
                            passageId = passageResult.data.passage_id;
                            // Clear old question IDs from sub-questions since old questions were deleted
                            subQuestions.forEach(subQ => {
                                delete subQ.dataset.questionId;
                            });
                        } else {
                            showMessage(`Lỗi tạo đoạn văn: ${passageResult.message}`, 'error');
                            errors++;
                        }
                    } catch (error) {
                        showMessage(`Lỗi tạo đoạn văn: ${error.message}`, 'error');
                        errors++;
                    }
                }

                // Submit each sub-question
                for (let i = 0; i < subQuestions.length; i++) {
                    const subQ = subQuestions[i];
                    const questionNumber = subQ.querySelector('.sub-question-number').value;
                    const content = subQ.querySelector('.question-content').value.trim();
                    const correctAnswer = subQ.querySelector('input[type="radio"]:checked')?.value;
                    const explanation = subQ.querySelector('.explanation').value.trim() || null;

                    const optionElements = subQ.querySelectorAll('.sub-options-grid .option-content');
                    const options = {
                        A: optionElements[0]?.value.trim() || '',
                        B: optionElements[1]?.value.trim() || '',
                        C: optionElements[2]?.value.trim() || '',
                        D: optionElements[3]?.value.trim() || ''
                    };

                    try {
                        const formData = new FormData();
                        formData.append('test_id', testId);
                        formData.append('part', part);
                        formData.append('question_number', questionNumber);
                        formData.append('passage_id', passageId);
                        formData.append('content', content);
                        formData.append('correct_answer', correctAnswer);
                        formData.append('explanation', explanation);
                        formData.append('options', JSON.stringify(options));

                        const response = await fetch('/IS207-UIT/server/index.php?path=/api/questions', {
                            method: 'POST',
                            body: formData
                        });

                        const result = await response.json();

                        console.log(`Sub-Question ${questionNumber} API Response:`, {
                            status: response.status,
                            success: result.success,
                            message: result.message,
                            data: result.data,
                            fullResult: result
                        });

                        if (result.success) {
                            created++;
                        } else {
                            showMessage(`Câu ${questionNumber}: ${result.message}`, 'error');
                            errors++;
                        }
                    } catch (error) {
                        showMessage(`Câu ${questionNumber}: ${error.message}`, 'error');
                        errors++;
                    }
                }
            } catch (error) {
                showMessage(`Lỗi submit nhóm câu hỏi: ${error.message}`, 'error');
                errors++;
            }

            return { created, errors };
        }
    </script>
    <script src="../js/api.js"></script>
</body>
</html>