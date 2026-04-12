<!DOCTYPE html>
<html lang="vi">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Form Nhập Câu Hỏi TOEIC</title>
	<link href="../styles/questionsStyle.css" rel="stylesheet">
	<style>
		:root {
			--primary-color: #4F46E5;
			--primary-hover: #4338CA;
			--card-bg: #FFFFFF;
			--text-main: #1F2937;
			--border-color: #D1D5DB;
			--danger-color: #EF4444;
		}

		/* Form Section Styling */
		.form-section {
			background-color: var(--card-bg);
			padding: 20px 25px;
			border-radius: 8px;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
			margin-bottom: 25px;
			border-left: 5px solid #4CAF50;
		}

		.form-section h3 {
			border-bottom: 1px solid var(--border-color);
			padding-bottom: 10px;
			margin-bottom: 20px;
		}

		.form-grid {
			display: grid;
			grid-template-columns: 2fr 1fr;
			gap: 15px;
		}

		.form-group {
			display: flex;
			flex-direction: column;
			margin-bottom: 15px;
		}

		.full-width {
			grid-column: 1 / -1;
		}

		.form-group label {
			font-weight: 600;
			margin-bottom: 6px;
			font-size: 13px;
		}

		.required-mark {
			color: var(--danger-color);
		}

		.form-group input[type="text"],
		.form-group textarea {
			width: 100%;
			padding: 8px 12px;
			border: 1px solid var(--border-color);
			border-radius: 6px;
			font-size: 14px;
			box-sizing: border-box;
			transition: 0.2s;
		}

		.form-group input:focus,
		.form-group textarea:focus {
			outline: none;
			border-color: var(--primary-color);
			box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
		}

		.form-group textarea {
			resize: vertical;
			min-height: 60px;
		}

		.form-actions {
			display: flex;
			justify-content: space-between;
			margin-top: 15px;
			padding-top: 15px;
			border-top: 1px dashed var(--border-color);
		}

		.checkbox-group-wrapper {
			display: flex;
			gap: 20px;
		}

		.checkbox-group {
			display: flex;
			align-items: center;
		}

		.checkbox-group input[type="checkbox"] {
			width: 16px;
			height: 16px;
			margin-right: 8px;
			cursor: pointer;
			accent-color: var(--primary-color);
		}

		.checkbox-group label {
			cursor: pointer;
			font-weight: normal;
			margin-bottom: 0;
		}

		.form-section .btn-submit {
			background-color: var(--primary-color);
			color: white;
			border: none;
			padding: 10px 24px;
			font-size: 14px;
			font-weight: 600;
			border-radius: 6px;
			cursor: pointer;
			transition: 0.2s;
		}

		.form-section .btn-submit:hover {
			background-color: var(--primary-hover);
		}
	</style>
</head>

<body>
	<?php include('./components/metadata.php'); ?>
	<?php include('./components/navBar.php'); ?>
	<?php include('./components/header.php'); ?>

	<div class="container-wrapper">
		
		<div class="form-section">
			<h3 style="margin-top: 0; font-size: 20px;">Tạo Bài Thi Mới</h3>
			<form id="createTestForm">
				<div class="form-grid">
					<div class="form-group">
						<label>Tiêu đề <span class="required-mark">*</span></label>
						<input type="text" name="title" required placeholder="Nhập tiêu đề...">
					</div>
					<div class="form-group full-width">
						<label>Mô tả</label>
						<textarea name="description" placeholder="Nhập mô tả chi tiết..."></textarea>
					</div>
				</div>
				<div class="form-actions">
					<div class="checkbox-group-wrapper">
						<div class="checkbox-group">
							<input type="checkbox" name="is_premium" value="1">
							<label>Premium</label>
						</div>
						<div class="checkbox-group">
							<input type="checkbox" name="is_active" value="1" checked>
							<label>Kích hoạt</label>
						</div>
					</div>
					<button type="submit" class="btn-submit">Thêm Bài Thi</button>
				</div>
			</form>
		</div>

		<div class="test-config">
			<h3 style="margin-top: 0; color: #333;">Cấu Hình Đề Thi & Câu Hỏi</h3>
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

		<div id="messageBox" class="message-box"></div>

		<div id="partInfo" class="part-info"></div>
		<div class="header-actions">
			<button class="btn btn-add" onclick="addBlock('single')">+ Thêm Câu Đơn</button>
			<button class="btn btn-add-group" onclick="addBlock('group')">+ Thêm Cụm Câu Hỏi</button>
			<button class="btn btn-delete-all" onclick="deleteAllBlocks()">
				<i class="bx bx-trash-alt" style="font-size: 1.2rem; vertical-align: -0.125em; margin-right: 5px;"></i>Xóa Tất Cả</button>
			<button class="btn btn-submit" onclick="submitData(event)">
				<i class="bx bx-save" style="font-size: 1.2rem; vertical-align: -0.125em; margin-right: 5px;"></i>Lưu Bài Test</button>
		</div>

		<div id="questions-container"></div>
	</div>

	<template id="single-question-template">
		<div class="question-block single-type" data-type="single">
			<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
				<div class="badge single block-title">Câu hỏi đơn</div>
				<button class="btn-remove" onclick="removeBlock(this)">Xóa</button>
			</div>

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
					<label><i class="bx bx-camera-alt" style="font-size: 1.2rem; vertical-align: -0.125em; "></i> Hình ảnh <span class="media-required-badge" style="color: red;"></span></label>
					<input type="file" accept="image/*" class="image-file" onchange="previewMedia(this, 'image')">
					<small class="media-hint" style="color: #666;">Tùy chọn</small>
					<div class="preview-container"></div>
				</div>
				<div class="upload-item">
					<label><i class="bx bx-volume-full" style="font-size: 1.2rem; vertical-align: -0.125em; "></i> Âm thanh <span class="media-required-badge" style="color: red;"></span></label>
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
				<button class="btn-remove" onclick="removeBlock(this)">Xóa</button>
			</div>

			<div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 15px;">
				<div class="upload-item">
					<label> <i class="bx bx-camera-alt" style="font-size: 1.2rem; vertical-align: -0.125em; "></i> Hình ảnh <span class="media-required-badge" style="color: red;"></span></label>
					<input type="file" accept="image/*" class="group-image-file" onchange="previewMedia(this, 'image')">
					<small class="media-hint" style="color: #666;">Tùy chọn</small>
					<div class="preview-container"></div>
				</div>
				<div class="upload-item">
					<label> <i class="bx bx-volume-full" style="font-size: 1.2rem; vertical-align: -0.125em; "></i> Âm thanh <span class="media-required-badge" style="color: red;"></span></label>
					<input type="file" accept="audio/*" class="group-audio-file" onchange="previewMedia(this, 'audio')">
					<small class="media-hint" style="color: #666;">Tùy chọn</small>
					<div class="preview-container"></div>
				</div>
				<div class="upload-item">
					<label> <i class="bx bx-file" style="font-size: 1.2rem; vertical-align: -0.125em; "></i> Đoạn văn (Passages)</label>
					<textarea class="form-control passage-content" placeholder="Dán đoạn văn dùng chung vào đây..." style="height: 120px;"></textarea>
				</div>
			</div>

			<div class="sub-questions-container"></div>

			<button class="btn-add-sub" onclick="addSubQuestionBtn(this)">+ Thêm 1 câu hỏi vào cụm</button>
		</div>
	</template>

	<script>
		/**
		 * ==========================================
		 * 1. CẤU HÌNH & TRẠNG THÁI (STATE & CONFIG)
		 * ==========================================
		 */
		const URL_PARAMS = new URLSearchParams(window.location.search);
		const ACTION_PARAM = URL_PARAMS.get('action');
		const TEST_ID_PARAM = URL_PARAMS.get('test_id');

		const PART_CONFIG = {
			1: { name: 'Ảnh', requiresImage: true, requiresAudio: false, requiresContent: false },
			2: { name: 'Câu hỏi ngắn', requiresImage: false, requiresAudio: true, requiresContent: true },
			3: { name: 'Hội thoại', requiresImage: false, requiresAudio: true, requiresContent: true },
			4: { name: 'Độc thoại', requiresImage: false, requiresAudio: true, requiresContent: true },
			5: { name: 'Đọc câu hoàn chỉnh', requiresImage: false, requiresAudio: false, requiresContent: true },
			6: { name: 'Điền từ', requiresImage: false, requiresAudio: false, requiresContent: true },
			7: { name: 'Đọc hiểu', requiresImage: false, requiresAudio: false, requiresContent: true },
		};

		const AppState = {
			globalBlockCounter: 0,
			loadedQuestionIds: new Set(),
			loadedPassageIds: new Set(),
			allTestQuestionNumbers: new Set()
		};


		/**
		 * ==========================================
		 * 2. KHỞI TẠO ỨNG DỤNG (INITIALIZATION)
		 * ==========================================
		 */
		document.addEventListener('DOMContentLoaded', initApp);

		function initApp() {
			setupUIFromParams();
			setupEventListeners();
			loadTestsData();
		}

		function setupUIFromParams() {
			document.getElementById('partSelect').disabled = true;

			if (ACTION_PARAM === 'create') {
				toggleCreateTestForm(true);
				toggleOtherForms(false);
			} else if (ACTION_PARAM === 'edit' && TEST_ID_PARAM) {
				toggleCreateTestForm(false);
				toggleOtherForms(true);
				
				// Đợi loadTests xong rồi trigger chọn test
				setTimeout(() => {
					const testSelect = document.getElementById('testSelect');
					if (testSelect.querySelector(`option[value="${TEST_ID_PARAM}"]`)) {
						testSelect.value = TEST_ID_PARAM;
						onTestChange();
						setTimeout(() => {
							document.getElementById('partSelect').value = '1';
							onPartChange();
						}, 300);
					}
				}, 500);
			} else {
				toggleCreateTestForm(true);
				toggleOtherForms(true);
				addBlock('single');
			}
		}

		function setupEventListeners() {
			const createForm = document.getElementById('createTestForm');
			if (createForm) {
				createForm.addEventListener('submit', handleCreateTestSubmit);
			}
		}


		/**
		 * ==========================================
		 * 3. XỬ LÝ GIAO DIỆN (VIEW UPDATES)
		 * ==========================================
		 */
		function toggleCreateTestForm(show) {
			const formSection = document.querySelector('.form-section');
			if (formSection) formSection.style.display = show ? 'block' : 'none';
		}

		function toggleOtherForms(show) {
			const displayStyle = show ? 'block' : 'none';
			const elements = ['.test-config', '#messageBox', '#partInfo', '.header-actions', '#questions-container'];
			
			elements.forEach(selector => {
				const el = document.querySelector(selector);
				if (el) el.style.display = displayStyle;
			});
		}

		function showMessage(message, type) {
			const messageBox = document.getElementById('messageBox');
			messageBox.textContent = message;
			messageBox.className = `message-box ${type}`;

			if (type === 'success') {
				setTimeout(() => { messageBox.className = 'message-box'; }, 5000);
			}
		}

		function updateMediaBadges(block, part) {
			const config = PART_CONFIG[parseInt(part)];
			if (!config) return;

			const updateEls = (selector, required, reqText, hintText) => {
				const labels = block.querySelectorAll(`${selector} .media-required-badge`);
				const hints = block.querySelectorAll(`${selector} .media-hint`);
				labels.forEach(l => l.textContent = required ? reqText : '');
				hints.forEach(h => h.textContent = hintText);
			};

			updateEls('.upload-item:nth-child(2)', config.requiresAudio, '(Bắt buộc)', config.requiresAudio ? 'MP3, WAV, OGG - tối đa 50MB' : 'Tùy chọn');
			updateEls('.upload-item:nth-child(1)', config.requiresImage, '(Bắt buộc)', config.requiresImage ? 'JPG, PNG, GIF - tối đa 5MB' : 'Tùy chọn');
		}

		function updateQuestionCount() {
			const singleQCount = document.querySelectorAll('.single-type').length;
			const subQCount = Array.from(document.querySelectorAll('.group-type')).reduce((sum, group) => {
				return sum + group.querySelectorAll('.sub-question-item').length;
			}, 0);

			const countElement = document.getElementById('questionCount');
			if (countElement) countElement.textContent = (singleQCount + subQCount).toString();
		}


		/**
		 * ==========================================
		 * 4. GIAO TIẾP MẠNG (NETWORK / API REQUESTS)
		 * ==========================================
		 */
		async function loadTestsData() {
			try {
				const response = await fetch('/api/tests');
				if (!response.ok) throw new Error(`HTTP ${response.status}: ${await response.text()}`);
				
				const result = await response.json();
				if (!result.success || !Array.isArray(result.data)) throw new Error('Định dạng dữ liệu không hợp lệ');

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
				console.error('Error loading tests:', error);
				showMessage('Lỗi tải danh sách đề thi', 'error');
			}
		}

		async function handleCreateTestSubmit(e) {
			e.preventDefault();
			const form = e.target;
			const formData = new FormData(form);
			
			const data = {
				title: formData.get('title'),
				description: formData.get('description'),
				is_premium: formData.get('is_premium') ? 1 : 0,
				is_active: formData.get('is_active') ? 1 : 0
			};

			try {
				const response = await fetch('/api/tests', {
					method: 'POST',
					headers: { 'Content-Type': 'application/json' },
					body: JSON.stringify(data)
				});
				const result = await response.json();

				if (result.success) {
					showMessage('Tạo bài thi thành công!', 'success');
					form.reset();
					toggleCreateTestForm(false);
					toggleOtherForms(true);
					
					const testSelect = document.getElementById('testSelect');
					testSelect.innerHTML = '<option value="">-- Chọn đề thi --</option>';
					await loadTestsData();

					setTimeout(() => {
						if (result.data && result.data.id) {
							testSelect.value = result.data.id;
							onTestChange();
							setTimeout(() => {
								document.getElementById('partSelect').value = '1';
								onPartChange();
							}, 300);
						}
					}, 500);
				} else {
					showMessage(`Lỗi: ${result.message || 'Không thể tạo bài thi'}`, 'error');
				}
			} catch (error) {
				console.error('Error creating test:', error);
				showMessage('Lỗi tạo bài thi', 'error');
			}
		}

		async function loadSavedQuestionsToForm() {
			const testId = document.getElementById('testSelect').value;
			const part = document.getElementById('partSelect').value;
			if (!testId || !part) return;

			AppState.loadedQuestionIds.clear();
			AppState.loadedPassageIds.clear();

			try {
				const response = await fetch(`/api/questions/${testId}`);
				if (!response.ok) throw new Error(`HTTP ${response.status}`);
				const result = await response.json();

				AppState.allTestQuestionNumbers.clear();
				if (result.success && Array.isArray(result.data)) {
					result.data.forEach(q => {
						if (q.question_number) AppState.allTestQuestionNumbers.add(parseInt(q.question_number));
					});
				}

				if (!result.success || !result.data || result.data.length === 0) {
					deleteAllBlocks();
					addBlock('single');
					return;
				}

				const partQuestions = result.data.filter(q => parseInt(q.part) === parseInt(part));
				if (partQuestions.length === 0) {
					deleteAllBlocks();
					addBlock('single');
					return;
				}

				partQuestions.sort((a, b) => parseInt(a.question_number) - parseInt(b.question_number));
				deleteAllBlocks();

				const groupQuestions = partQuestions.filter(q => q.passage_id);
				let passagesMap = {};

				if (groupQuestions.length > 0) {
					const passagesRes = await fetch(`/api/passages/${testId}`);
					if (passagesRes.ok) {
						const pResult = await passagesRes.json();
						if (pResult.success && pResult.data) {
							pResult.data.forEach(p => passagesMap[p.id] = p);
						}
					}
				}

				const passageToQuestions = {};
				groupQuestions.forEach(q => {
					if (!passageToQuestions[q.passage_id]) passageToQuestions[q.passage_id] = [];
					passageToQuestions[q.passage_id].push(q);
				});

				Object.values(passageToQuestions).forEach(arr => arr.sort((a, b) => parseInt(a.question_number) - parseInt(b.question_number)));

				const processedPassages = new Set();

				partQuestions.forEach(q => {
					if (q.passage_id) {
						if (!processedPassages.has(q.passage_id)) {
							processedPassages.add(q.passage_id);
							addBlock('group');
							const blockDiv = document.querySelector('.question-block.group-type:last-child');
							if (blockDiv) fillGroupQuestionData(passagesMap[q.passage_id], passageToQuestions[q.passage_id], blockDiv);
						}
					} else {
						addBlock('single');
						const blockDiv = document.querySelector('.question-block.single-type:last-child');
						if (blockDiv) fillSingleQuestionData(q, blockDiv);
					}
				});

			} catch (error) {
				console.error('Error loading saved questions:', error);
				showMessage('Lỗi tải câu hỏi đã lưu', 'warning');
				deleteAllBlocks();
				addBlock('single');
			}
		}


		/**
		 * ==========================================
		 * 5. LOGIC SỰ KIỆN GIAO DIỆN (EVENT HANDLERS)
		 * ==========================================
		 */
		function onTestChange() {
			const testId = document.getElementById('testSelect').value;
			const partSelect = document.getElementById('partSelect');

			if (!testId) {
				showMessage('Vui lòng chọn đề thi', 'error');
				partSelect.value = '';
				document.getElementById('partInfo').classList.remove('show');
				return;
			}
			partSelect.disabled = false;
			partSelect.value = '1';
			onPartChange();
		}

		function onPartChange() {
			const part = document.getElementById('partSelect').value;
			if (!part) {
				document.getElementById('partInfo').classList.remove('show');
				return;
			}

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

			document.querySelectorAll('.question-block').forEach(block => updateMediaBadges(block, part));
			loadSavedQuestionsToForm();
		}


		/**
		 * ==========================================
		 * 6. QUẢN LÝ DOM (DOM MANIPULATION & BUILDERS)
		 * ==========================================
		 */
		function addBlock(type) {
			const testId = document.getElementById('testSelect').value;
			const part = document.getElementById('partSelect').value;

			if (!testId) return showMessage('Vui lòng chọn đề thi trước', 'error');
			if (!part) return showMessage('Vui lòng chọn part trước', 'error');

			AppState.globalBlockCounter++;
			const container = document.getElementById('questions-container');
			const templateId = type === 'single' ? 'single-question-template' : 'group-question-template';
			const clone = document.getElementById(templateId).content.cloneNode(true);
			const blockDiv = clone.querySelector('.question-block');
			
			blockDiv.dataset.blockId = AppState.globalBlockCounter;
			const nextNumber = getLastQuestionNumber() + 1;

			if (type === 'single') {
				const numberInput = blockDiv.querySelector('.question-number');
				if (numberInput) numberInput.value = nextNumber;
				blockDiv.querySelectorAll('.correct-radio').forEach(r => r.name = `correct_block_${AppState.globalBlockCounter}`);
			} else {
				const subContainer = blockDiv.querySelector('.sub-questions-container');
				for (let i = 0; i < 3; i++) {
					subContainer.appendChild(createSubQuestionDOM(AppState.globalBlockCounter, nextNumber + i));
				}
			}

			container.appendChild(clone);
			updateMediaBadges(blockDiv, part);
			updateQuestionCount();
		}

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

		function addSubQuestionBtn(button) {
			const blockDiv = button.closest('.question-block');
			const subContainer = blockDiv.querySelector('.sub-questions-container');
			const nextNumber = getLastQuestionNumber() + 1;
			subContainer.appendChild(createSubQuestionDOM(blockDiv.dataset.blockId, nextNumber));
			updateQuestionCount();
		}

		function removeSubQuestion(button) {
			const subQuestion = button.closest('.sub-question-item');
			const block = subQuestion.closest('.question-block');
			subQuestion.remove();

			if (block.querySelectorAll('.sub-question-item').length === 0) {
				block.remove();
			}
			
			updateAllQuestionNumbers();
			updateQuestionCount();
		}

		function removeBlock(button) {
			button.closest('.question-block').remove();
			updateAllQuestionNumbers();
			updateQuestionCount();
		}

		function deleteAllBlocks() {
			const container = document.getElementById('questions-container');
			if (container) container.innerHTML = '';
			AppState.globalBlockCounter = 0;
			updateQuestionCount();
		}


		/**
		 * ==========================================
		 * 7. XỬ LÝ DỮ LIỆU FORM (DATA POPULATION)
		 * ==========================================
		 */
		function fillSingleQuestionData(question, block) {
			if (!block) return;
			const setVal = (selector, val) => { const el = block.querySelector(selector); if(el) el.value = val; };

			setVal('.question-number', question.question_number);
			setVal('.question-content', question.content || '');
			
			const optionInputs = block.querySelectorAll('.options-container .option-content');
			if (question.options && question.options.length === 4) {
				question.options.forEach((opt, idx) => { if (optionInputs[idx]) optionInputs[idx].value = opt.content || ''; });
			}

			block.querySelectorAll('.correct-radio').forEach(radio => {
				if (radio.value === question.correct_answer) radio.checked = true;
			});

			setVal('.explanation', (question.explanation && question.explanation !== 'null') ? question.explanation : '');

			const mediaSection = block.querySelector('.media-upload-section');
			if (mediaSection) {
				if (question.image_url) {
					const imgIn = mediaSection.querySelector('.upload-item:nth-child(1) input[type="file"]');
					const imgPre = mediaSection.querySelector('.upload-item:nth-child(1) .preview-container');
					if (imgIn) imgIn.dataset.existingUrl = question.image_url;
					if (imgPre) imgPre.innerHTML = `<img src="${question.image_url}" alt="Question image" style="max-width: 200px;">`;
				}
				if (question.audio_url) {
					const audIn = mediaSection.querySelector('.upload-item:nth-child(2) input[type="file"]');
					const audPre = mediaSection.querySelector('.upload-item:nth-child(2) .preview-container');
					if (audIn) audIn.dataset.existingUrl = question.audio_url;
					if (audPre) audPre.innerHTML = `<audio controls src="${question.audio_url}" style="width: 100%;"></audio>`;
				}
			}

			block.dataset.questionId = question.id;
			AppState.loadedQuestionIds.add(question.id);
		}

		function fillGroupQuestionData(passage, subQuestions, block) {
			if (!block) return;

			const passageInput = block.querySelector('.passage-content');
			if (passageInput) passageInput.value = passage.content || '';

			const imageUploadItem = block.querySelector('.group-image-file')?.closest('.upload-item');
			if (passage.image_url && imageUploadItem) {
				const imgIn = block.querySelector('.group-image-file');
				const imgPre = imageUploadItem.querySelector('.preview-container');
				if (imgIn) imgIn.dataset.existingUrl = passage.image_url;
				if (imgPre) imgPre.innerHTML = `<img src="${passage.image_url}" alt="Passage image" style="max-width: 200px;">`;
			}

			const audioUploadItem = block.querySelector('.group-audio-file')?.closest('.upload-item');
			if (passage.audio_url && audioUploadItem) {
				const audIn = block.querySelector('.group-audio-file');
				const audPre = audioUploadItem.querySelector('.preview-container');
				if (audIn) audIn.dataset.existingUrl = passage.audio_url;
				if (audPre) audPre.innerHTML = `<audio controls src="${passage.audio_url}" style="width: 100%;"></audio>`;
			}

			block.dataset.passageId = passage.id;
			AppState.loadedPassageIds.add(passage.id);

			const subContainer = block.querySelector('.sub-questions-container');
			subContainer.innerHTML = ''; // Clear defaults

			if (subQuestions && subQuestions.length > 0) {
				subQuestions.forEach((subQ, index) => {
					const subDiv = createSubQuestionDOM(block.dataset.blockId, subQ.question_number || index + 1);
					subDiv.querySelector('.sub-question-number').value = subQ.question_number || index + 1;
					subDiv.querySelector('.question-content').value = subQ.content || '';

					const optIn = subDiv.querySelectorAll('.sub-options-grid .option-content');
					if (subQ.options && subQ.options.length === 4) {
						subQ.options.forEach((opt, idx) => { if (optIn[idx]) optIn[idx].value = opt.content || ''; });
					}

					subDiv.querySelectorAll('input[type="radio"]').forEach(radio => {
						if (radio.value === subQ.correct_answer) radio.checked = true;
					});

					const expIn = subDiv.querySelector('.explanation');
					if (expIn) expIn.value = (subQ.explanation && subQ.explanation !== 'null') ? subQ.explanation : '';
					
					subDiv.dataset.questionId = subQ.id;
					AppState.loadedQuestionIds.add(subQ.id);
					subContainer.appendChild(subDiv);
				});
			}
		}


		/**
		 * ==========================================
		 * 8. LOGIC GỬI DỮ LIỆU & VALIDATE (SUBMIT)
		 * ==========================================
		 */
		async function submitData(event) {
			const testId = document.getElementById('testSelect').value;
			const part = document.getElementById('partSelect').value;

			if (!testId) return showMessage('Vui lòng chọn đề thi', 'error');
			if (!part) return showMessage('Vui lòng chọn part', 'error');

			const blocks = document.querySelectorAll('.question-block');
			if (blocks.length === 0 && AppState.loadedQuestionIds.size === 0 && AppState.loadedPassageIds.size === 0) {
				return showMessage('Vui lòng thêm ít nhất 1 câu hỏi', 'error');
			}

			if (!validateAllBlocks(blocks, part)) return; // Dừng nếu validate thất bại

			const currentQuestionIds = new Set();
			const currentPassageIds = new Set();
			
			blocks.forEach(block => {
				if (block.dataset.questionId) currentQuestionIds.add(parseInt(block.dataset.questionId));
				if (block.dataset.passageId) currentPassageIds.add(parseInt(block.dataset.passageId));
				block.querySelectorAll('.sub-question-item').forEach(sub => {
					if (sub.dataset.questionId) currentQuestionIds.add(parseInt(sub.dataset.questionId));
				});
			});

			const submitBtn = event?.target || document.querySelector('.btn-submit');
			const originalText = submitBtn?.textContent;
			if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Đang lưu...'; }

			const deletedQuestionIds = Array.from(AppState.loadedQuestionIds).filter(id => !currentQuestionIds.has(id));
			const deletedPassageIds = Array.from(AppState.loadedPassageIds).filter(id => !currentPassageIds.has(id));

			try {
				// Xóa dữ liệu cũ
				for (const qId of deletedQuestionIds) await fetch('/api/questions/' + qId, { method: 'DELETE' });
				for (const pId of deletedPassageIds) await fetch('/api/passages/' + pId, { method: 'DELETE' });

				let totalCreated = 0;
				let totalErrors = 0;
				
				// Submit từng block
				for (let block of blocks) {
					if (block.dataset.type === 'single') {
						const success = await submitSingleQuestionAPI(block, testId, part);
						success ? totalCreated++ : totalErrors++;
					} else {
						const result = await submitGroupQuestionsAPI(block, testId, part);
						totalCreated += result.created;
						totalErrors += result.errors;
					}
				}

				if (totalErrors === 0) {
					const deleteMsg = deletedQuestionIds.length > 0 || deletedPassageIds.length > 0 ? ` (xóa ${deletedQuestionIds.length + deletedPassageIds.length} câu/cụm)` : '';
					showMessage(`Thành công! Đã lưu ${totalCreated} câu hỏi${deleteMsg}`, 'success');
					
					const savedPart = part;
					deleteAllBlocks();
					AppState.loadedQuestionIds.clear();
					AppState.loadedPassageIds.clear();
					document.getElementById('testSelect').value = testId;
					document.getElementById('partSelect').value = savedPart;
					
					setTimeout(() => loadSavedQuestionsToForm(), 800);
				} else {
					showMessage(`Lưu ${totalCreated} câu hỏi thành công, ${totalErrors} lỗi`, 'warning');
				}
			} catch (error) {
				console.error('Error submitting data:', error);
				showMessage('Lỗi lưu dữ liệu', 'error');
			} finally {
				if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = originalText || 'Lưu Bài Test'; }
			}
		}

		function validateAllBlocks(blocks, part) {
			let isValid = true;
			const seenQuestionNumbers = new Set();

			const checkError = (condition, msg) => {
				if (condition) { showMessage(msg, 'error'); isValid = false; return true; }
				return false;
			};

			blocks.forEach((block, blockIndex) => {
				if (!isValid) return; 

				if (block.dataset.type === 'single') {
					const qNumStr = block.querySelector('.question-number')?.value.trim();
					if (checkError(!qNumStr, `Câu #${blockIndex + 1}: Vui lòng nhập số thứ tự câu hỏi`)) return;
					
					const qNum = parseInt(qNumStr);
					if (checkError(seenQuestionNumbers.has(qNum), `Câu #${blockIndex + 1}: Số thứ tự ${qNumStr} bị trùng lặp`)) return;
					if (checkError(AppState.allTestQuestionNumbers.has(qNum) && !block.dataset.questionId, `Câu #${blockIndex + 1}: Số thứ tự ${qNumStr} đã tồn tại trong đề`)) return;
					if (checkError(qNum < 1 || qNum > 200, `Câu #${blockIndex + 1}: Số thứ tự phải từ 1-200`)) return;
					seenQuestionNumbers.add(qNum);

					if (checkError(!block.querySelector('.question-content')?.value.trim(), `Câu #${blockIndex + 1}: Vui lòng nhập nội dung câu hỏi`)) return;

					const options = block.querySelectorAll('.option-content');
					options.forEach(opt => { checkError(!opt.value.trim(), `Câu #${blockIndex + 1}: Vui lòng nhập đầy đủ 4 đáp án`); });
					if (!isValid) return;

					if (checkError(!block.querySelector('.correct-radio:checked'), `Câu #${blockIndex + 1}: Vui lòng chọn đáp án đúng`)) return;

					const hasMedia = (block.querySelector('.audio-file')?.files[0] || block.querySelector('.audio-file')?.dataset.existingUrl) || 
									 (block.querySelector('.image-file')?.files[0] || block.querySelector('.image-file')?.dataset.existingUrl);
					const hasAudio = (block.querySelector('.audio-file')?.files[0] || block.querySelector('.audio-file')?.dataset.existingUrl);
					
					if (checkError(part === '1' && !hasMedia, `Câu #${blockIndex + 1}: Part 1 cần hình ảnh hoặc âm thanh`)) return;
					if (checkError(['2', '3', '4'].includes(part) && !hasAudio, `Câu #${blockIndex + 1}: Part ${part} cần âm thanh`)) return;

				} else {
					if (checkError(!block.querySelector('.passage-content')?.value.trim(), `Cụm #${blockIndex + 1}: Vui lòng nhập nội dung đoạn văn`)) return;
					
					const subQs = block.querySelectorAll('.sub-question-item');
					if (checkError(subQs.length === 0, `Cụm #${blockIndex + 1}: Vui lòng thêm ít nhất 1 câu hỏi`)) return;

					const hasMedia = (block.querySelector('.group-audio-file')?.files[0] || block.querySelector('.group-audio-file')?.dataset.existingUrl) || 
									 (block.querySelector('.group-image-file')?.files[0] || block.querySelector('.group-image-file')?.dataset.existingUrl);
					const hasAudio = (block.querySelector('.group-audio-file')?.files[0] || block.querySelector('.group-audio-file')?.dataset.existingUrl);

					if (checkError(part === '1' && !hasMedia, `Cụm #${blockIndex + 1}: Part 1 cần hình ảnh hoặc âm thanh`)) return;
					if (checkError(['2', '3', '4'].includes(part) && !hasAudio, `Cụm #${blockIndex + 1}: Part ${part} cần âm thanh`)) return;

					subQs.forEach((subQ, subIndex) => {
						if (!isValid) return;
						const qNumStr = subQ.querySelector('.sub-question-number')?.value.trim();
						if (checkError(!qNumStr, `Cụm #${blockIndex + 1}, Câu #${subIndex + 1}: Thiếu số thứ tự`)) return;
						
						const qNum = parseInt(qNumStr);
						if (checkError(seenQuestionNumbers.has(qNum), `Cụm #${blockIndex + 1}, Câu #${subIndex + 1}: Trùng số thứ tự ${qNumStr}`)) return;
						if (checkError(AppState.allTestQuestionNumbers.has(qNum) && !subQ.dataset.questionId, `Cụm #${blockIndex + 1}, Câu #${subIndex + 1}: Số ${qNumStr} đã tồn tại`)) return;
						if (checkError(qNum < 1 || qNum > 200, `Cụm #${blockIndex + 1}, Câu #${subIndex + 1}: Số thứ tự phải từ 1-200`)) return;
						seenQuestionNumbers.add(qNum);

						if (checkError(!subQ.querySelector('.question-content')?.value.trim(), `Cụm #${blockIndex + 1}, Câu #${subIndex + 1}: Thiếu nội dung`)) return;
						subQ.querySelectorAll('.option-content').forEach(opt => { checkError(!opt.value.trim(), `Cụm #${blockIndex + 1}, Câu #${subIndex + 1}: Thiếu đáp án`); });
						if (checkError(!subQ.querySelector('input[type="radio"]:checked'), `Cụm #${blockIndex + 1}, Câu #${subIndex + 1}: Chưa chọn đáp án đúng`)) return;
					});
				}
			});
			return isValid;
		}

		async function submitSingleQuestionAPI(block, testId, part) {
			try {
				const opts = block.querySelectorAll('.options-container .option-item .option-content');
				const options = { A: opts[0]?.value.trim(), B: opts[1]?.value.trim(), C: opts[2]?.value.trim(), D: opts[3]?.value.trim() };

				const questionId = block.dataset.questionId;
				if (questionId) await fetch('/api/questions/' + questionId, { method: 'DELETE' });

				const formData = new FormData();
				formData.append('test_id', testId);
				formData.append('part', part);
				formData.append('question_number', block.querySelector('.question-number').value);
				formData.append('content', block.querySelector('.question-content').value.trim() || null);
				formData.append('correct_answer', block.querySelector('.correct-radio:checked')?.value);
				formData.append('explanation', block.querySelector('.explanation').value.trim() || null);
				formData.append('options', JSON.stringify(options));

				const audioIn = block.querySelector('.audio-file');
				if (audioIn?.files[0]) formData.append('audio_file', audioIn.files[0]);
				else if (audioIn?.dataset.existingUrl) formData.append('audio_url', audioIn.dataset.existingUrl);

				const imageIn = block.querySelector('.image-file');
				if (imageIn?.files[0]) formData.append('image_file', imageIn.files[0]);
				else if (imageIn?.dataset.existingUrl) formData.append('image_url', imageIn.dataset.existingUrl);

				const response = await fetch('/api/questions', { method: 'POST', body: formData });
				const result = await response.json();

				if (!result.success) {
					showMessage(`Lỗi: ${result.message}`, 'error');
					return false;
				}
				return true;
			} catch (error) {
				return false;
			}
		}

		async function submitGroupQuestionsAPI(block, testId, part) {
			let created = 0, errors = 0;
			try {
				let passageId = null;
				const passageContent = block.querySelector('.passage-content').value.trim();
				const subQuestions = block.querySelectorAll('.sub-question-item');

				const existingPassageId = block.dataset.passageId;
				if (existingPassageId) await fetch('/api/passages/' + existingPassageId, { method: 'DELETE' });

				const pFormData = new FormData();
				pFormData.append('test_id', testId);
				pFormData.append('part', part);
				if (passageContent) pFormData.append('content', passageContent);

				const aIn = block.querySelector('.group-audio-file');
				if (aIn?.files[0]) pFormData.append('audio_file', aIn.files[0]);
				else if (aIn?.dataset.existingUrl) pFormData.append('audio_url', aIn.dataset.existingUrl);

				const iIn = block.querySelector('.group-image-file');
				if (iIn?.files[0]) pFormData.append('image_file', iIn.files[0]);
				else if (iIn?.dataset.existingUrl) pFormData.append('image_url', iIn.dataset.existingUrl);

				const pRes = await fetch('/api/passages', { method: 'POST', body: pFormData });
				const pResult = await pRes.json();

				if (pResult.success) {
					passageId = pResult.data.passage_id;
					subQuestions.forEach(sq => delete sq.dataset.questionId);
				} else {
					errors++; return { created, errors };
				}

				for (let subQ of subQuestions) {
					try {
						const opts = subQ.querySelectorAll('.sub-options-grid .option-content');
						const options = { A: opts[0]?.value.trim(), B: opts[1]?.value.trim(), C: opts[2]?.value.trim(), D: opts[3]?.value.trim() };
						
						const qFormData = new FormData();
						qFormData.append('test_id', testId);
						qFormData.append('part', part);
						qFormData.append('passage_id', passageId);
						qFormData.append('question_number', subQ.querySelector('.sub-question-number').value);
						qFormData.append('content', subQ.querySelector('.question-content').value.trim());
						qFormData.append('correct_answer', subQ.querySelector('input[type="radio"]:checked')?.value);
						qFormData.append('explanation', subQ.querySelector('.explanation').value.trim() || null);
						qFormData.append('options', JSON.stringify(options));

						const qRes = await fetch('/api/questions', { method: 'POST', body: qFormData });
						const qResult = await qRes.json();
						qResult.success ? created++ : errors++;
					} catch (e) { errors++; }
				}
			} catch (e) { errors++; }
			return { created, errors };
		}


		/**
		 * ==========================================
		 * 9. TIỆN ÍCH CHUNG (HELPER UTILS)
		 * ==========================================
		 */
		function updateAllQuestionNumbers() {
			let currentNumber = 1;
			document.querySelectorAll('.question-block').forEach(block => {
				if (block.classList.contains('single-type')) {
					const input = block.querySelector('.question-number');
					if (input) { input.value = currentNumber++; }
				} else {
					block.querySelectorAll('.sub-question-item').forEach(subQ => {
						const input = subQ.querySelector('.sub-question-number');
						if (input) { input.value = currentNumber++; }
					});
				}
			});
		}

		function getLastQuestionNumber() {
			const inputs = [...document.querySelectorAll('.single-type .question-number'), ...document.querySelectorAll('.sub-question-item .sub-question-number')];
			if (inputs.length === 0) {
				return AppState.allTestQuestionNumbers.size > 0 ? Math.max(...Array.from(AppState.allTestQuestionNumbers)) : 0;
			}
			return Math.max(...inputs.map(i => parseInt(i.value) || 0), 0);
		}

		function previewMedia(input, type) {
			let container = input.nextElementSibling;
			while (container && !container.classList.contains('preview-container')) {
				container = container.nextElementSibling;
			}
			if (!container) {
				const uploadItem = input.closest('.upload-item');
				if (uploadItem) container = uploadItem.querySelector('.preview-container');
			}
			if (!container) return;

			container.innerHTML = '';
			if (!input.files || !input.files[0]) return;

			const file = input.files[0];
			const maxSize = type === 'audio' ? 50 * 1024 * 1024 : 5 * 1024 * 1024;
			if (file.size > maxSize) {
				showMessage(`File quá lớn! Tối đa ${type === 'audio' ? '50MB' : '5MB'}`, 'error');
				input.value = ''; return;
			}

			const url = URL.createObjectURL(file);
			const isImage = type === 'image' || (type === 'auto' && file.type.startsWith('image'));
			const isAudio = type === 'audio' || (type === 'auto' && file.type.startsWith('audio'));

			if (isImage) {
				container.innerHTML = `<img src="${url}" style="max-width: 200px;">`;
			} else if (isAudio) {
				container.innerHTML = `<audio controls src="${url}" style="width: 100%;"></audio>`;
			}
		}

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
						optionInputs[i].value = options[i].replace(/^[A-Da-d1-4][\.\)\s\-\/\]]+/, '').trim();
					}
				}
			}
		}
	</script>
	<script src="../js/questions.js"></script>

	<?php include('./components/footer.php'); ?>
</body>
</html>