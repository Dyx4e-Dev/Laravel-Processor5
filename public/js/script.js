// ========================== 
// SIMULATION STATE 
// ========================== 

// Penyimpanan state utama simulasi 
let singleCustomers = []; 
let multiCustomers = []; 
let simulationRunning = false; 
let singleTime = 0; 
let multiTime = 0; 
let customerId = 0; 

// Interval simulasi 
let singleInterval = null; 
let multiInterval = null; 
let timeInterval = null; 

// Interval untuk animasi titik pada teks loading 
let loadingDotsInterval = null; 
let loadingDotCount = 0; 

// ========================== 
// DOM ELEMENTS 
// ========================== 

// Elemen DOM utama 
const singleQueueElement = document.getElementById('single-queue'); 
const multiQueueElement = document.getElementById('multi-queue'); 
const singleCustomersElement = document.getElementById('single-customers'); 
const singleTimeElement = document.getElementById('single-time'); 
const multiCustomersElement = document.getElementById('multi-customers'); 
const multiTimeElement = document.getElementById('multi-time'); 
const addCustomerBtn = document.getElementById('add-customer'); 
const startSimulationBtn = document.getElementById('start-simulation'); 
const resetSimulationBtn = document.getElementById('reset-simulation'); 

// Tombol-tombol 
const workloadButtons = document.querySelectorAll('.workload-btn'); 
const runBenchmarkBtn = document.getElementById('run-benchmark'); 

const quizOptions = document.querySelectorAll('.quiz-option'); 
const getRecommendationBtn = document.getElementById('get-recommendation'); 
const recommendationResult = document.getElementById('recommendation-result'); 

const scrollToTopBtn = document.getElementById('scrollToTop'); 
const glossaryTerms = document.querySelectorAll('.glossary-term'); 

// ========================== 
// NOTIFICATIONS 
// ========================== 

window.notify = function(type, message) {
    const area = document.getElementById('notification-area');
    if (!area) return;

    const toast = document.createElement('div');
    toast.className = `notif ${type}`;

    const iconMap = {
        success: 'bx-check-circle',
        warning: 'bx-error-circle',
        error: 'bx-x-circle',
        info: 'bx-info-circle'
    };

    const icon = iconMap[type] || iconMap.info;

    toast.innerHTML = `
        <div style="display:flex; align-items:center; gap:12px;">
            <i class="bx ${icon} notif-icon"></i>
            <span class="notif-text">${message}</span>
        </div>
    `;

    area.appendChild(toast);

    setTimeout(() => toast.remove(), 3500);
};


// ========================== 
// ANALOGY SIMULATION 
// ========================== 

// Menambah pelanggan ke antrian 
function addCustomer() { 
    if (simulationRunning) { 
        notify('warning', 'Simulasi sedang berjalan! Tunggu hingga selesai.');
        return; 
    } 
    customerId++; 
    const customer = { id: customerId, processingTime: 2 }; 
    singleCustomers.push({ ...customer }); 
    multiCustomers.push({ ...customer }); 
    updateQueueDisplay(); 
    updateCustomerCounts(); 
} 

// Menampilkan pelanggan di antrian 
function updateQueueDisplay() { 
    singleQueueElement.innerHTML = ''; 
    singleCustomers.forEach(customer => { 
        const customerElement = createCustomerElement(customer); 
        singleQueueElement.appendChild(customerElement); 
    }); 
    multiQueueElement.innerHTML = ''; 
    multiCustomers.forEach(customer => { 
        const customerElement = createCustomerElement(customer); 
        multiQueueElement.appendChild(customerElement); 
    }); 
} 

// Membuat elemen pelanggan 
function createCustomerElement(customer) { 
    const element = document.createElement('div'); 
    element.className = 'customer'; 
    element.textContent = customer.id; 
    element.dataset.id = customer.id; 
    element.title = `Pelanggan ${customer.id}`; 
    return element; 
} 

// Update jumlah pelanggan 
function updateCustomerCounts() { 
    singleCustomersElement.textContent = singleCustomers.length; 
    multiCustomersElement.textContent = multiCustomers.length; 
} 

// Update tampilan waktu 
function updateTimeDisplays() { 
    singleTimeElement.textContent = `${singleTime}s`; 
    multiTimeElement.textContent = `${multiTime}s`; 
} 

// Memulai simulasi 
function startSimulation() { 
    if (singleCustomers.length === 0) { 
        notify('warning', 'Tidak ada pelanggan! Silakan tambah pelanggan terlebih dahulu.');
        return; 
    } 
    if (simulationRunning) return; 
    simulationRunning = true; 
    startSimulationBtn.disabled = true; 
    addCustomerBtn.disabled = true; 
    singleTime = 0; 
    multiTime = 0; 
    updateTimeDisplays(); 
    startSimulationIntervals(); 
    startTimeUpdate(); 
} 

// Memulai interval simulasi 
function startSimulationIntervals() { 
    singleInterval = setInterval(() => { 
        if (!simulationRunning || singleCustomers.length === 0) { 
            clearInterval(singleInterval); 
            return; 
        } 
        const customers = document.querySelectorAll('#single-queue .customer'); 
        if (customers.length > 0) { 
            customers[0].classList.add('processing'); 
        } 
        setTimeout(() => { 
            if (singleCustomers.length > 0) { 
                singleCustomers.shift(); 
                updateQueueDisplay(); 
                updateCustomerCounts(); 
                if (singleCustomers.length === 0) { 
                    clearInterval(singleInterval); 
                    checkSimulationComplete(); 
                } 
            } 
        }, 200); 
    }, 1000); 

    multiInterval = setInterval(() => { 
        if (!simulationRunning || multiCustomers.length === 0) { 
            clearInterval(multiInterval); 
            return; 
        } 
        const customers = document.querySelectorAll('#multi-queue .customer'); 
        const customersToProcess = Math.min(4, multiCustomers.length); 
        for (let i = 0; i < customersToProcess; i++) { 
            customers[i].classList.add('processing'); 
        } 
        setTimeout(() => { 
            if (multiCustomers.length > 0) { 
                multiCustomers.splice(0, customersToProcess); 
                updateQueueDisplay(); 
                updateCustomerCounts(); 
                if (multiCustomers.length === 0) { 
                    clearInterval(multiInterval); 
                    checkSimulationComplete(); 
                } 
            } 
        }, 200); 
    }, 1000); 
} 

// Memulai update waktu simulasi 
function startTimeUpdate() { 
    timeInterval = setInterval(() => { 
        if (!simulationRunning) { 
            clearInterval(timeInterval); 
            return; 
        } 
        if (singleCustomers.length > 0) singleTime++; 
        if (multiCustomers.length > 0) multiTime++; 
        updateTimeDisplays(); 
        if (singleCustomers.length === 0 && multiCustomers.length === 0) { 
            clearInterval(timeInterval); 
            simulationRunning = false; 
            startSimulationBtn.disabled = false; 
            addCustomerBtn.disabled = false; 
            showComparisonResult(); 
        } 
    }, 1000); 
} 

// Cek apakah simulasi selesai 
function checkSimulationComplete() { 
    if (singleCustomers.length === 0 && multiCustomers.length === 0) { 
        simulationRunning = false; 
        startSimulationBtn.disabled = false; 
        addCustomerBtn.disabled = false; 
        showComparisonResult(); 
    } 
} 

// Menampilkan hasil perbandingan simulasi 
function showComparisonResult() { 
    const efficiency = ((singleTime - multiTime) / singleTime * 100).toFixed(1); 

    let message = ''; 
    let type = 'info'; 

    if (multiTime < singleTime) { 
        message = `Multi-core ${efficiency}% lebih cepat!\n⏱️ Single: ${singleTime}s | ⏱️ Multi: ${multiTime}s`; 
        type = 'success'; 
    } else if (multiTime > singleTime) { 
        message = `Single-core ${Math.abs(efficiency)}% lebih cepat!\n⏱️ Single: ${singleTime}s | ⏱️ Multi: ${multiTime}s`; 
        type = 'success'; 
    } else { 
        message = `Kedua arsitektur sama cepat!\n⏱️ Waktu: ${singleTime}s`; 
        type = 'info'; 
    } 

    notify(type, message); 
}


// Mereset simulasi 
function resetSimulation() { 
    clearInterval(singleInterval); 
    clearInterval(multiInterval); 
    clearInterval(timeInterval); 
    simulationRunning = false; 
    singleCustomers = []; 
    multiCustomers = []; 
    customerId = 0; 
    singleTime = 0; 
    multiTime = 0; 
    updateQueueDisplay(); 
    updateCustomerCounts(); 
    updateTimeDisplays(); 
    startSimulationBtn.disabled = false; 
    addCustomerBtn.disabled = false; 
} 

// ========================== 
// BENCHMARK 
// ========================== 

let updateChart; 

// Inisialisasi chart benchmark dengan D3.js 
function initializeBenchmarkChart() {
    const svgWidth = 600;
    const svgHeight = 450;
    const margin = { top: 20, right: 30, bottom: 40, left: 70 };
    const width = svgWidth - margin.left - margin.right;
    const height = svgHeight - margin.top - margin.bottom;
    d3.select("#benchmark-chart").html("");
    const svg = d3.select("#benchmark-chart")
        .append("svg")
        .attr("viewBox", `0 0 ${width + margin.left + margin.right} ${height + margin.top + margin.bottom}`)
        .attr("width", "100%")
        .attr("height", "100%")
        .attr("preserveAspectRatio", "xMidYMid meet");
    const g = svg.append("g")
        .attr("transform", `translate(${margin.left},${margin.top})`);
    const xScale = d3.scaleBand()
        .range([0, width])
        .padding(0.1);
    const yScale = d3.scaleLinear()
        .range([height, 0]);
    const xAxis = d3.axisBottom(xScale);
    const yAxis = d3.axisLeft(yScale);
    g.append("g")
        .attr("class", "x-axis")
        .attr("transform", `translate(0,${height})`);
    g.append("g")
        .attr("class", "y-axis");
    g.append("text")
        .attr("transform", "rotate(-90)")
        .attr("y", 0 - margin.left)
        .attr("x", 0 - (height / 2))
        .attr("dy", "1em")
        .style("text-anchor", "middle")
        .style("fill", "#fff")
        .style("font-size", "12px")
        .text("Performance (%)");
    const tooltip = d3.select("body")
        .append("div")
        .attr("class", "chart-tooltip")
        .style("position", "fixed")
        .style("background", "rgba(0, 0, 0, 0.8)")
        .style("color", "white")
        .style("padding", "8px")
        .style("border-radius", "4px")
        .style("font-size", "12px")
        .style("pointer-events", "none")
        .style("opacity", 0)
        .style("z-index", "1000");

    // Fungsi untuk memperbarui chart
    function updateChartFunction(workload) {
        const data = Object.entries(benchmarkData[workload]).map(([core, performance]) => ({
            core,
            performance
        }));
        xScale.domain(data.map(d => d.core));
        yScale.domain([0, 100]);
        g.select(".x-axis")
            .transition()
            .duration(1000)
            .ease(d3.easeBackOut)
            .call(xAxis);
        g.select(".y-axis")
            .transition()
            .duration(1000)
            .ease(d3.easeBackOut)
            .call(yAxis);
        const bars = g.selectAll(".bar")
            .data(data, d => d.core);
        bars.exit()
            .transition()
            .duration(1000)
            .ease(d3.easeBackOut)
            .attr("y", height)
            .attr("height", 0)
            .remove();
        const barsEnter = bars.enter()
            .append("rect")
            .attr("class", "bar")
            .attr("x", d => xScale(d.core))
            .attr("y", d => yScale(d.performance))
            .attr("width", xScale.bandwidth())
            .attr("height", d => height - yScale(d.performance))
            .attr("fill", "#00ff9d")
            .on("mouseover", function (event, d) {
                tooltip.transition()
                    .duration(200)
                    .style("opacity", .9);
                tooltip.html(`${d.core}<br/>Performance: ${d.performance}%`)
                    .style("left", (event.clientX + 10) + "px")
                    .style("top", (event.clientY - 28) + "px");
                d3.select(this).style("opacity", 0.7);
            })
            .on("mouseout", function () {
                tooltip.transition()
                    .duration(500)
                    .style("opacity", 0);
                d3.select(this).style("opacity", 1);
            });
        barsEnter.merge(bars)
            .transition()
            .duration(1000)
            .ease(d3.easeBackOut)
            .attr("x", d => xScale(d.core))
            .attr("y", d => yScale(d.performance))
            .attr("width", xScale.bandwidth())
            .attr("height", d => height - yScale(d.performance));
        const labels = g.selectAll(".label")
            .data(data, d => d.core);
        labels.exit().remove();
        labels.enter()
            .append("text")
            .attr("class", "label")
            .attr("text-anchor", "middle")
            .attr("fill", "#fff")
            .attr("x", d => xScale(d.core) + xScale.bandwidth() / 2)
            .attr("y", d => yScale(d.performance) - 5)
            .text(d => `${d.performance}%`)
            .merge(labels)
            .text(d => `${d.performance}%`)
            .transition()
            .duration(1000)
            .ease(d3.easeBackOut)
            .attr("x", d => xScale(d.core) + xScale.bandwidth() / 2)
            .attr("y", d => yScale(d.performance) - 5);
    }
    updateChartFunction('gaming');
    return updateChartFunction;
}



// Menjalankan benchmark 
function runBenchmark() { 
    const activeWorkload = document.querySelector('.workload-btn.active').dataset.workload; 
    const originalText = runBenchmarkBtn.innerHTML; 
    runBenchmarkBtn.innerHTML = '<span class="loading">⏳</span> Menjalankan...'; 
    runBenchmarkBtn.disabled = true; 
    setTimeout(() => { 
        if (updateChart) { 
            updateChart(activeWorkload); 
        } 
        showBenchmarkResults(activeWorkload); 
        runBenchmarkBtn.innerHTML = originalText; 
        runBenchmarkBtn.disabled = false; 
    }, 1500); 
} 

// Helper untuk perbandingan core 
function getCoreComparison(results) { 
    const single = results["1 Core"]; 
    const multiCore = Object.values(results).reduce((a, b) => Math.max(a, b)); 
    const multiCoreLabel = Object.keys(results).find(k => results[k] === multiCore); 
    return { 
        single: single, 
        multi: multiCore, 
        multiLabel: multiCoreLabel, 
        improvement: multiCore - single 
    }; 
} 

// Menampilkan popup hasil benchmark 
function showBenchmarkResults(workload) { 
    const results = benchmarkData[workload]; 
    const popup = createResultsPopup(workload, results); 
    document.body.appendChild(popup); 
    setTimeout(() => { 
        popup.classList.add('show'); 
    }, 10); 
} 

// Format nama workload 
function formatWorkload(w) { 
    return { 
        gaming: "Gaming", 
        "video-editing": "Video Editing", 
        "web-browsing": "Web Browsing" 
    }[w]; 
} 

// Membangun rekomendasi core terbaik 
function buildBestCoreReco(workload) { 
    const benchmark = benchmarkResults.find(b => b.name.toLowerCase() === workload.replace('-', ' ')); 
    if (benchmark && benchmark.result) { 
        return `<p>${benchmark.result.best_core} Core</p>`; 
    } 
    return `<p>Data tidak tersedia</p>`; 
} 

// Membangun perbandingan core 
function buildCoreComparison(results) { 
    const core1 = results[0]; 
    const bestCore = Math.max(...results); 
    const bestIndex = results.indexOf(bestCore) + 1; 

    return ` 
        <div class="core-item"> 
            <span>Performa 1 Core</span> 
            <strong>${core1}</strong> 
        </div> 
        <div class="core-item"> 
            <span>Core Terbaik (Core ${bestIndex})</span> 
            <strong>${bestCore}</strong> 
        </div> 
    `; 
} 

// Membangun rekomendasi CPU 
function buildCPUReco(workload) { 
    const benchmark = benchmarkResults.find(b => b.name.toLowerCase() === workload.replace('-', ' ')); 
    if (benchmark && benchmark.best_cpus) { 
        return benchmark.best_cpus 
            .map(r => ` 
                <div class="cpu-item"> 
                    <div class="cpu-name">${r.cpu_name}</div> 
                    <div class="cpu-reason">${r.description}</div> 
                </div> 
            `) 
            .join(""); 
    } 
    return `<p>Data rekomendasi CPU tidak tersedia</p>`; 
} 

// Membangun analisis 
function buildAnalysis(workload) { 
    const a = { 
        gaming: "Game modern sangat bergantung pada single-core performance. 6 core memberikan performa 95% dari 8 core, namun jauh lebih efisien sehingga budget bisa dialihkan ke GPU.", 
        "video-editing": "Rendering video sangat scalable dengan core tambahan. 8 core menawarkan waktu render jauh lebih cepat dan stabil dalam timeline berat.", 
        "web-browsing": "Aplikasi browsing dan office tidak memanfaatkan banyak core. 4 core modern sudah sangat cukup untuk multitasking harian tanpa bottleneck." 
    }; 
    return `<p>${a[workload]}</p>`; 
} 

// Membangun daftar performa 
function buildPerformanceList(results) { 
    return Object.entries(results).map(([core, performance]) => ` 
        <div class="core-item"> 
            <span>${core}</span> 
            <strong>${performance}%</strong> 
        </div> 
    `).join(''); 
} 

// Membuat elemen popup hasil benchmark 
function createResultsPopup(workload, results) { 
    const popup = document.createElement('div'); 
    popup.className = 'benchmark-results-popup'; 
    popup.innerHTML = ` 
        <div class="benchmark-results-content"> 
            <div class="benchmark-results-header"> 
                <h3>Hasil Benchmark</h3> 
                <div class="workload-type">${formatWorkload(workload)}</div> 
            </div> 
            <div class="reco-core-list"> 
                <h4>Rekomendasi Core Terbaik</h4> 
                <div id="bestCoreList">${buildBestCoreReco(workload)}</div> 
            </div> 
            <div class="cpu-reco-list"> 
                <h4>Rekomendasi CPU Spesifik</h4> 
                <div id="cpuRecommendationList">${buildCPUReco(workload)}</div> 
            </div> 
            <div class="analysis-section"> 
                <h4>Analisis</h4> 
                <div id="benchmarkAnalysis">${buildAnalysis(workload)}</div> 
            </div> 
            <div class="benchmark-results-footer"> 
                <button class="close-results-btn" onclick="closeResultsPopup(this)">Tutup Hasil</button> 
            </div> 
        </div> 
    `; 
    return popup; 
} 

// Menutup popup hasil 
function closeResultsPopup(button) { 
    const popup = button.closest('.benchmark-results-popup'); 
    popup.classList.remove('show'); 
    setTimeout(() => { 
        if (document.body.contains(popup)) { 
            document.body.removeChild(popup); 
        } 
    }, 300); 
} 

// ========================== 
// RECOMMENDATION 
// ========================== 

let currentLaptopIndex = 0; 
let laptopRecommendations = []; 

// Mendapatkan rekomendasi 
function getRecommendation() { 
    const answers = { usage: [], budget: null, app_usage: null }; 

    document.querySelectorAll('.quiz-question').forEach((question, index) => { 
        if (index === 0) { 
            const selectedOptions = question.querySelectorAll('.quiz-option.selected'); 
            selectedOptions.forEach(option => answers.usage.push(option.dataset.value)); 
        } else if (index === 1) { 
            const selectedOption = question.querySelector('.quiz-option.selected'); 
            if (selectedOption) answers.budget = selectedOption.dataset.value; 
        } else if (index === 2) { 
            const selectedOption = question.querySelector('.quiz-option.selected'); 
            if (selectedOption) answers.app_usage = selectedOption.dataset.value; 
        } 
    }); 

    if (answers.usage.length === 0 || !answers.budget || !answers.app_usage) { 
        notify('warning', 'Silakan jawab semua pertanyaan terlebih dahulu!');
        return; 
    } 

    fetch('/recommend', { 
        method: 'POST', 
        headers: { 
            'Content-Type': 'application/json', 
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
        }, 
        body: JSON.stringify({ 
            budget: answers.budget, 
            usage: answers.usage, 
            app_usage: answers.app_usage 
        }) 
    }) 
    .then(response => response.json()) 
    .then(data => { 
        if (data.laptops && data.laptops.length > 0) { 
            laptopRecommendations = data.laptops; 
            currentLaptopIndex = 0; 
            displayLaptopRecommendation(); 
        } else { 
            recommendationResult.innerHTML = ` 
                <h3>😔 Tidak ada rekomendasi yang cocok</h3> 
                <p>Maaf, tidak ada laptop yang sesuai dengan kriteria Anda saat ini. Silakan coba kombinasi jawaban yang berbeda.</p> 
            `; 
        } 
        recommendationResult.classList.add('show'); 
        recommendationResult.scrollIntoView({ behavior: 'smooth', block: 'center' }); 
    }) 
    .catch(error => { 
        console.error('Error:', error); 
        notify('error', 'Terjadi kesalahan saat mengambil rekomendasi. Silakan coba lagi.');
    }); 
} 

// Menampilkan rekomendasi laptop 
function displayLaptopRecommendation() { 
    const laptop = laptopRecommendations[currentLaptopIndex]; 
    const totalLaptops = laptopRecommendations.length; 

    recommendationResult.innerHTML = ` 
        <div class="core-display-card"> 
            <div class="display-header"> 
                <span class="rank-badge">RECOMMENDATION ${currentLaptopIndex + 1}/${totalLaptops}</span> 
                <h2 class="laptop-title-gradient">${laptop.name}</h2> 
            </div> 
            <div class="display-content"> 
                <div class="visual-engine"> 
                    <button id="prev-laptop" class="nav-btn-cyber" ${currentLaptopIndex === 0 ? 'disabled' : ''}> 
                        <ion-icon name="caret-back-outline"></ion-icon> 
                    </button> 
                    <div class="laptop-frame"> 
                        ${laptop.photo ? `<img src="${laptop.photo}" alt="${laptop.name}">` : `<div class="no-img-cyber"><i class='bx bx-laptop'></i></div>`} 
                    </div> 
                    <button id="next-laptop" class="nav-btn-cyber" ${currentLaptopIndex === totalLaptops - 1 ? 'disabled' : ''}> 
                        <ion-icon name="caret-forward-outline"></ion-icon> 
                    </button> 
                </div> 
                <div class="specs-cyber-grid"> 
                    <div class="grid-cell"><label>Brand</label><p>${laptop.brand}</p></div> 
                    <div class="grid-cell"><label>Processor</label><p>${laptop.processor}</p></div> 
                    <div class="grid-cell"><label>Memory (RAM)</label><p>${laptop.ram}</p></div> 
                    <div class="grid-cell"><label>Storage</label><p>${laptop.storage}</p></div> 
                    <div class="grid-cell"><label>Graphics</label><p>${laptop.vga}</p></div> 
                    <div class="grid-cell"><label>Screen Size</label><p>${laptop.screen_size}</p></div> 
                </div> 
                <div class="price-neon-container"> 
                    <div class="price-tag"> 
                        <small>ESTIMATED PRICE</small> 
                        <h3>Rp ${parseFloat(laptop.price).toLocaleString('id-ID')}</h3> 
                    </div> 
                </div> 
            </div> 
        </div> 
    `; 

    document.getElementById('prev-laptop').addEventListener('click', () => { 
        if (currentLaptopIndex > 0) { 
            currentLaptopIndex--; 
            displayLaptopRecommendation(); 
        } 
    }); 

    document.getElementById('next-laptop').addEventListener('click', () => { 
        if (currentLaptopIndex < totalLaptops - 1) { 
            currentLaptopIndex++; 
            displayLaptopRecommendation(); 
        } 
    }); 
} 

// ========================== 
// QUIZ SYSTEM 
// ========================== 

document.addEventListener('DOMContentLoaded', function() { 
    const rawData = typeof rawQuizzesData !== 'undefined' ? rawQuizzesData : []; 
    if (rawData.length === 0) { 
        console.warn('Warning: No quiz data available'); 
    } 

    const questions = rawData.map(item => ({ 
        q: item.question, 
        a: [item.option_a, item.option_b, item.option_c, item.option_d], 
        correct: mapAnswerToIndex(item.answer) 
    })); 

    function mapAnswerToIndex(answer) { 
        if (!isNaN(answer) && answer !== null && answer !== '') return parseInt(answer); 
        const mapping = { 'a': 0, 'b': 1, 'c': 2, 'd': 3 }; 
        const key = String(answer).toLowerCase().trim(); 
        return mapping[key] !== undefined ? mapping[key] : 0; 
    } 

    const formContainer = document.getElementById('quiz-form-container'); 
    const quizPopup = document.getElementById('quiz-popup'); 
    const quizForm = document.querySelector('.quiz-form'); 
    const closeQuizBtn = document.getElementById('close-quiz-btn'); 
    const timerBar = document.getElementById('timer-bar'); 
    const timerText = document.getElementById('timer-text'); 
    const questionText = document.getElementById('question-text'); 
    const answerContainer = document.getElementById('answer-options'); 
    const counterText = document.getElementById('question-counter'); 
    const progressBar = document.getElementById('progress-bar'); 
    const resultView = document.getElementById('quiz-result-view'); 
    const quizContent = document.getElementById('quiz-content'); 
    const quizHeader = document.querySelector('.quiz-header'); 
    const totalQuestionsSpan = document.getElementById('total-questions'); 

    if (!quizPopup || !quizContent || !resultView) { 
        console.error('Error: Quiz popup elements not found in DOM'); 
        return; 
    } 

    if (totalQuestionsSpan) { 
        totalQuestionsSpan.innerText = questions.length; 
    } 

    let currentIdx = 0; 
    let score = 0; 
    let timeLeft = 10; 
    let timerInterval = null; 
    let isAnswering = false; 
    let savedQuizResult = null; 
    let quizFinishedAndSaved = false; 

    function showPopup() { 
        if (!quizPopup) return; 
        quizPopup.classList.add('show'); 
        quizPopup.style.display = 'flex'; 
        quizPopup.style.opacity = '1'; 
        document.body.style.overflow = 'hidden'; 
    } 

    function hidePopup() { 
        if (!quizPopup) return; 
        quizPopup.classList.remove('show'); 
        setTimeout(() => { 
            quizPopup.style.display = 'none'; 
            document.body.style.overflow = 'auto'; 
        }, 300); 
    } 

    function displayResultCard(nama, score, rewardStatus) { 
        if (formContainer) { 
            formContainer.innerHTML = `<div class="quiz-result-card" style="text-align: center; width: 100%; max-width: 100%; overflow: hidden; box-sizing: border-box; padding: 20px;"><h2 class="quiz-title">Anda sudah melakukan quiz, Terimakasih</h2><p style="margin-bottom: 10px; word-break: break-word;">${nama}</p><div class="score-circle" style="width: 100px; height: 100px; background: #6e8efb; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 20px auto; color: white; font-size: 2.5rem; font-weight: bold; box-shadow: 0 4px 15px rgba(0,0,0,0.2); flex-shrink: 0;">${score}</div><div style="display: inline-block; padding: 5px 15px; background: #e0f2f1; color: #00796b; border-radius: 20px; font-weight: bold; word-break: break-word; max-width: 90%;">${rewardStatus}</div></div>`; 
        } 
    } 

    function saveToDatabase(finalScoreValue) { 
        const namaInput = document.getElementById('nama'); 
        const emailInput = document.getElementById('email'); 
        const whoInput = document.getElementById('who-explain'); 
        const tokenInput = document.querySelector('input[name="_token"]'); 

        if (!namaInput || !emailInput || !whoInput || !tokenInput) { 
            console.error('Form inputs not found'); 
            // Fallback: set result dengan data dari form meski token hilang
            quizFinishedAndSaved = true;
            savedQuizResult = {
                nama: namaInput?.value || '',
                score: finalScoreValue,
                reward_status: finalScoreValue === 10 ? 'Hadiah Teh Kotak & Stiker' : (finalScoreValue >= 7 ? 'Hadiah Stiker' : 'Coba Lagi')
            };
            return; 
        } 

        const payload = { 
            nama: namaInput.value, 
            email: emailInput.value, 
            who_explain: whoInput.value, 
            score: finalScoreValue, 
            _token: tokenInput.value 
        }; 

        fetch('/submit-quiz', { 
            method: 'POST', 
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': payload._token }, 
            body: JSON.stringify(payload) 
        }) 
        .then(res => res.json()) 
        .then(data => { 
            if (data && data.success) { 
                savedQuizResult = data.data || null; 
                quizFinishedAndSaved = true;
                console.log('Quiz result saved:', savedQuizResult);
            } else {
                // Fallback jika success false tapi response valid
                quizFinishedAndSaved = true;
                savedQuizResult = {
                    nama: payload.nama,
                    score: finalScoreValue,
                    reward_status: finalScoreValue === 10 ? 'Hadiah Teh Kotak & Stiker' : (finalScoreValue >= 7 ? 'Hadiah Stiker' : 'Coba Lagi')
                };
            } 
        }) 
        .catch(err => { 
            console.error('Save failed:', err);
            // Fallback jika fetch gagal
            quizFinishedAndSaved = true;
            savedQuizResult = {
                nama: payload.nama,
                score: finalScoreValue,
                reward_status: finalScoreValue === 10 ? 'Hadiah Teh Kotak & Stiker' : (finalScoreValue >= 7 ? 'Hadiah Stiker' : 'Coba Lagi')
            };
        }); 
    } 

    function startQuiz() { 
        currentIdx = 0; 
        score = 0; 
        isAnswering = false; 
        quizContent.style.display = 'block'; 
        resultView.style.display = 'none'; 
        loadQuestion(); 
    } 

    function loadQuestion() { 
        if (currentIdx >= questions.length) { 
            showResults(); 
            return; 
        } 

        const q = questions[currentIdx]; 
        questionText.innerText = q.q; 
        counterText.innerText = `Soal ${currentIdx + 1} / ${questions.length}`; 
        progressBar.style.width = `${((currentIdx + 1) / questions.length) * 100}%`; 

        answerContainer.innerHTML = ''; 
        q.a.forEach((opt, index) => { 
            const btn = document.createElement('button'); 
            btn.className = 'answer-btn'; 
            btn.innerText = opt; 
            btn.type = 'button'; 
            btn.onclick = (e) => { 
                e.preventDefault(); 
                if (!isAnswering) handleAnswer(index, btn); 
            }; 
            answerContainer.appendChild(btn); 
        }); 

        resetTimer(); 
    } 

    function resetTimer() { 
        clearInterval(timerInterval); 
        timeLeft = 10; 
        timerBar.style.width = '100%'; 
        timerText.innerText = '10s'; 
        isAnswering = false; 

        timerInterval = setInterval(() => { 
            timeLeft--; 
            timerText.innerText = `${timeLeft}s`; 
            timerBar.style.width = `${(timeLeft / 10) * 100}%`; 
            if (timeLeft <= 0) { 
                clearInterval(timerInterval); 
                handleAnswer(-1, null); 
            } 
        }, 1000); 
    } 

    function handleAnswer(selectedIndex, clickedBtn) { 
        if (isAnswering) return; 
        isAnswering = true; 
        clearInterval(timerInterval); 

        const q = questions[currentIdx]; 
        const allBtns = answerContainer.querySelectorAll('.answer-btn'); 
        allBtns.forEach(btn => btn.classList.add('disabled')); 

        if (selectedIndex === q.correct) { 
            score++; 
            if (clickedBtn) clickedBtn.classList.add('correct-blink'); 
        } else { 
            if (clickedBtn) clickedBtn.classList.add('wrong-blink'); 
            allBtns[q.correct].classList.add('correct-blink'); 
        } 

        setTimeout(() => { 
            currentIdx++; 
            loadQuestion(); 
        }, 1500); 
    } 

    function showResults() { 
        clearInterval(timerInterval); 
        quizContent.style.display = 'none'; 
        if (quizHeader) quizHeader.style.display = 'none'; 
        resultView.style.display = 'block'; 

        const finalScore = score; 
        document.getElementById('final-score').innerText = finalScore; 
        let status = document.getElementById('score-status'); 
        let desc = document.getElementById('score-desc'); 

        if (finalScore === 10) { 
            status.innerText = "Selamat! 🎉"; 
            desc.innerText = "Kamu mendapatkan Hadiah Teh Kotak dan Stiker!"; 
        } else if (finalScore >= 7) { 
            status.innerText = "Bagus! 👍"; 
            desc.innerText = "Kamu mendapatkan Hadiah Stiker!"; 
        } else { 
            status.innerText = "Coba Lagi!"; 
            desc.innerText = "Belajar lagi materi Single vs Multi Core ya!"; 
        } 

        saveToDatabase(finalScore); 
    } 

    if (quizForm) { 
        quizForm.addEventListener('submit', function(e) { 
            e.preventDefault(); 
            const nama = document.getElementById('nama').value.trim(); 
            const email = document.getElementById('email').value.trim(); 
            const who = document.getElementById('who-explain').value; 

            if (nama && email && who) { 
                const submitBtn = quizForm.querySelector('button[type="submit"]'); 
                if (submitBtn) { 
                    submitBtn.disabled = true; 
                    submitBtn.innerText = 'Memproses...'; 
                } 
                showPopup(); 
                startQuiz(); 
            } else { 
                alert("Harap isi semua data terlebih dahulu!"); 
            } 
        }); 
    } 

    if (closeQuizBtn) { 
        closeQuizBtn.addEventListener('click', () => { 
            clearInterval(timerInterval); 
            hidePopup(); 
            
            // Tunggu sampai save response diterima atau timeout 2 detik
            const maxWait = 2000;
            const startTime = Date.now();
            
            const waitForResult = setInterval(() => {
                const elapsed = Date.now() - startTime;
                
                if (quizFinishedAndSaved && savedQuizResult) {
                    clearInterval(waitForResult);
                    
                    const nama = savedQuizResult.nama || savedQuizResult.name || document.getElementById('nama')?.value || ''; 
                    const skor = savedQuizResult.score !== undefined ? savedQuizResult.score : (typeof score === 'number' ? score : 0); 
                    const reward = savedQuizResult.reward_status || savedQuizResult.rewardStatus || ''; 
                    
                    displayResultCard(nama, skor, reward); 
                    
                    const submitBtn = quizForm.querySelector('button[type="submit"]'); 
                    if (submitBtn) { 
                        submitBtn.disabled = true; 
                        submitBtn.innerText = 'Sudah Mengikuti Quiz'; 
                    } 
                } else if (elapsed >= maxWait) {
                    // Timeout: gunakan data dari lokal jika server belum respond
                    clearInterval(waitForResult);
                    
                    if (quizFinishedAndSaved || savedQuizResult) {
                        const nama = (savedQuizResult?.nama) || document.getElementById('nama')?.value || ''; 
                        const skor = (savedQuizResult?.score) !== undefined ? savedQuizResult.score : score; 
                        const reward = (savedQuizResult?.reward_status) || (score === 10 ? 'Hadiah Teh Kotak & Stiker' : (score >= 7 ? 'Hadiah Stiker' : 'Coba Lagi')); 
                        
                        displayResultCard(nama, skor, reward); 
                        
                        const submitBtn = quizForm.querySelector('button[type="submit"]'); 
                        if (submitBtn) { 
                            submitBtn.disabled = true; 
                            submitBtn.innerText = 'Sudah Mengikuti Quiz'; 
                        } 
                    }
                }
            }, 100);
        }); 
    } 
}); 

// ========================== 
// FLOATING PARTICLES 
// ========================== 

function generateFloatingParticles() { 
    const container = document.getElementById('floating-particles'); 
    if (!container) return; 

    container.innerHTML = ''; 
    const particleCount = 60; 

    for (let i = 0; i < particleCount; i++) { 
        const particle = document.createElement('div'); 
        particle.className = 'particle'; 

        const size = Math.random() * 20 + 5; 
        particle.style.width = `${size}px`; 
        particle.style.height = `${size}px`; 

        const left = Math.random() * 100; 
        const top = Math.random() * 100; 
        particle.style.left = `${left}%`; 
        particle.style.top = `${top}%`; 

        const delay = Math.random() * 10; 
        particle.style.animationDelay = `${delay}s`; 

        const duration = Math.random() * 7 + 8; 
        particle.style.animationDuration = `${duration}s`; 

        container.appendChild(particle); 
    } 
} 

// ========================== 
// GENERAL UTILITIES 
// ========================== 

function initializeGlossary() { 
    glossaryTerms.forEach(term => { 
        const toggle = term.querySelector('.term-toggle'); 
        const definition = term.querySelector('.term-definition'); 
        const chevronIcon = term.querySelector('.chevron-icon'); 
        if (!toggle || !definition || !chevronIcon) return; 

        toggle.addEventListener('click', function (event) { 
            event.stopPropagation(); 
            const isExpanded = definition.classList.contains('expanded'); 
            closeAllGlossaryTerms(); 
            if (!isExpanded) { 
                definition.classList.add('expanded'); 
                term.classList.add('active'); 
                chevronIcon.classList.add('rotated'); 
            } else { 
                definition.classList.remove('expanded'); 
                term.classList.remove('active'); 
                chevronIcon.classList.remove('rotated'); 
            } 
        }); 

        term.addEventListener('click', function (event) { 
            if (event.target === term) { 
                const isExpanded = definition.classList.contains('expanded'); 
                closeAllGlossaryTerms(); 
                if (!isExpanded) { 
                    definition.classList.add('expanded'); 
                    term.classList.add('active'); 
                    chevronIcon.classList.add('rotated'); 
                } 
            } 
        }); 
    }); 
    initializeGlossaryClickOutside(); 
} 

function closeAllGlossaryTerms() { 
    glossaryTerms.forEach(term => { 
        const chevronIcon = term.querySelector('.chevron-icon'); 
        const definition = term.querySelector('.term-definition'); 
        if (chevronIcon && definition) { 
            definition.classList.remove('expanded'); 
            term.classList.remove('active'); 
            chevronIcon.classList.remove('rotated'); 
        } 
    }); 
} 

function initializeGlossaryClickOutside() { 
    document.addEventListener('click', function (event) { 
        const isGlossaryContainer = event.target.closest('.glossary-container'); 
        if (!isGlossaryContainer) { 
            closeAllGlossaryTerms(); 
        } 
    }); 
} 

function initializeSmoothScrolling() { 
    document.querySelectorAll('a[href^="#"]').forEach(anchor => { 
        anchor.addEventListener('click', function (e) { 
            e.preventDefault(); 
            const target = document.querySelector(this.getAttribute('href')); 
            if (target) { 
                target.scrollIntoView({ behavior: 'smooth', block: 'start' }); 
            } 
        }); 
    }); 
} 

function initializeScrollToTop() { 
    window.addEventListener('scroll', function () { 
        if (window.pageYOffset > 300) { 
            scrollToTopBtn.style.display = 'flex'; 
        } else { 
            scrollToTopBtn.style.display = 'none'; 
        } 
    }); 
    scrollToTopBtn.addEventListener('click', function () { 
        window.scrollTo({ top: 0, behavior: 'smooth' }); 
    }); 
} 

function initializeActiveNavigation() { 
    const sections = document.querySelectorAll('section'); 
    const navLinks = document.querySelectorAll('.nav-links a'); 

    window.addEventListener('scroll', function () { 
        let current = ''; 
        sections.forEach(section => { 
            const sectionTop = section.offsetTop; 
            if (pageYOffset >= sectionTop - 200) { 
                current = section.getAttribute('id'); 
            } 
        }); 
        navLinks.forEach(link => { 
            link.classList.remove('active'); 
            if (link.getAttribute('href').substring(1) === current) { 
                link.classList.add('active'); 
            } 
        }); 
    }); 
} 

function initializeHamburgerMenu() {
    const hamburger = document.getElementById('hamburger');
    const navContainer = document.querySelector('.nav-container');
    const navLinks = document.querySelector('.nav-links');

    if (!hamburger || !navLinks) return;

    // toggle menu
    hamburger.addEventListener('click', function (e) {
        e.stopPropagation(); // ⬅️ PENTING
        if (hamburger.classList.contains('active')) {
            hamburger.classList.remove('active');
            navContainer.classList.remove('active');
        } else {
            hamburger.classList.add('active');
            navContainer.classList.add('active');
        }
    });

    // klik menu → tutup
    navLinks.querySelectorAll('a').forEach(item => {
        item.addEventListener('click', () => {
            hamburger.classList.remove('active');
            navContainer.classList.remove('active');
        });
    });

    // klik luar → tutup
    document.addEventListener('click', () => {
        hamburger.classList.remove('active');
        navContainer.classList.remove('active');
    });
}


// ========================== 
// EVENT LISTENERS 
// ========================== 

function initializeEventListeners() { 
    if (addCustomerBtn) addCustomerBtn.addEventListener('click', addCustomer); 
    if (startSimulationBtn) startSimulationBtn.addEventListener('click', startSimulation); 
    if (resetSimulationBtn) resetSimulationBtn.addEventListener('click', resetSimulation); 

    workloadButtons.forEach(btn => { 
        btn.addEventListener('click', function () { 
            workloadButtons.forEach(b => b.classList.remove('active')); 
            this.classList.add('active'); 
            if (updateChart) updateChart(this.dataset.workload); 
        }); 
    }); 

    if (runBenchmarkBtn) runBenchmarkBtn.addEventListener('click', runBenchmark); 

    quizOptions.forEach(option => { 
        option.addEventListener('click', function () { 
            const parent = this.parentElement; 
            if (!parent) return; 

            if (parent.classList.contains('multiple')) { 
                this.classList.toggle('selected'); 
            } else { 
                parent.querySelectorAll('.quiz-option').forEach(o => o.classList.remove('selected')); 
                this.classList.add('selected'); 
            } 
        }); 
    }); 

    if (getRecommendationBtn) getRecommendationBtn.addEventListener('click', getRecommendation); 

    initializeSmoothScrolling(); 
    initializeScrollToTop(); 
    initializeActiveNavigation(); 
    initializeHamburgerMenu(); 
} 

// ========================== 
// LOADING SCREEN 
// ========================== 

function hideLoadingScreen() {
    const loader = document.getElementById('loading-screen');
    if (!loader) return;

    if (loadingDotsInterval) {
        clearInterval(loadingDotsInterval);
        loadingDotsInterval = null;
    }

    const txt = loader.querySelector('.loader-text');
    if (txt) txt.textContent = 'Loading';

    loader.classList.add('hide');

    loader.addEventListener('animationend', () => {
        loader.remove();
    }, { once: true });
}


function startLoadingDots() {
    if (loadingDotsInterval) return; // ⛔ cegah reset

    const el = document.querySelector('.loader-text');
    if (!el) return;

    loadingDotCount = 0;

    loadingDotsInterval = setInterval(() => {
        loadingDotCount = (loadingDotCount % 3) + 1;
        el.textContent = 'Loading' + '.'.repeat(loadingDotCount);
    }, 500);
}



// ========================== 
// INITIALIZATION 
// ========================== 


document.addEventListener('DOMContentLoaded', function () { 
    
    startLoadingDots(); 
    updateChart = initializeBenchmarkChart(); 
    initializeGlossary(); 
    initializeEventListeners(); 
    initializeParallax(); 
    initializeStickyHeader(); 
    generateFloatingParticles(); 
    updateCustomerCounts(); 
    updateTimeDisplays(); 

    setTimeout(() => {
        hideLoadingScreen();
    }, 1500); // 

    const el = document.getElementById('typed'); 
    if (el) { 
        const texts = ['Corewar', 'Single-core vs Multi-core']; 
        const typeSpeed = 75; 
        const deleteSpeed = 60; 
        const pauseAfterType = 1600; 

        const type = async (text) => { 
            for (let i = 0; i < text.length; i++) { 
                el.textContent += text[i]; 
                await new Promise(r => setTimeout(r, typeSpeed)); 
            } 
        }; 

        const del = async () => { 
            while (el.textContent.length) { 
                el.textContent = el.textContent.slice(0, -1); 
                await new Promise(r => setTimeout(r, deleteSpeed)); 
            } 
        }; 

        (async () => { 
            while (true) { 
                for (let i = 0; i < texts.length; i++) { 
                    await type(texts[i]); 
                    await new Promise(r => setTimeout(r, pauseAfterType)); 
                    await del(); 
                } 
            } 
        })(); 
    } 

    const revealElements = document.querySelectorAll(".reveal"); 
    const observer = new IntersectionObserver((entries) => { 
        entries.forEach(entry => { 
            if (entry.isIntersecting) { 
                entry.target.classList.add("show"); 
            } else { 
                entry.target.classList.remove("show"); 
            } 
        }); 
    }, { threshold: 0.3 }); 
    revealElements.forEach(el => observer.observe(el)); 

    console.log('Kelompok 5\nPerbedaan Single-Core vs Multi-core\nAqshal Virgiawan\nDika Pida Ismail\nFauzan Fathurrohman\nHarlan Ikhsan\nJasmine Haimana Wildan\nRafly Al Bukhary\nRidho Muhamad Ilham'); 
}); 


// ========================== 
// TEAM SWIPER 
// ========================== 

const teamSwiper = new Swiper('.team-swiper', { 
    effect: 'coverflow', 
    grabCursor: true, 
    centeredSlides: true, 
    slidesPerView: 3, 
    spaceBetween: 30, 
    loop: true, 
    autoplay: { 
        delay: 2000, 
        disableOnInteraction: true, 
    }, 
    coverflowEffect: { 
        rotate: 50, 
        stretch: 0, 
        depth: 100, 
        modifier: 1, 
        slideShadows: true, 
    }, 
    pagination: { 
        el: '.swiper-pagination', 
        clickable: true, 
    }, 
    breakpoints: { 
        320: { 
            slidesPerView: 1, 
            spaceBetween: 10, 
            coverflowEffect: { rotate: 30, depth: 60 }, 
        }, 
        768: { 
            slidesPerView: 2, 
            spaceBetween: 20, 
            coverflowEffect: { rotate: 40, depth: 80 }, 
        }, 
        1024: { 
            slidesPerView: 3, 
            spaceBetween: 30, 
            coverflowEffect: { rotate: 50, depth: 100 }, 
        }, 
    } 
}); 

document.addEventListener('click', function(event) { 
    const memberImageWrapper = event.target.closest('.member-image-wrapper'); 
    if (!memberImageWrapper) return; 

    const swiperSlide = memberImageWrapper.closest('.swiper-slide'); 
    if (!swiperSlide) return; 

    const isActive = swiperSlide.classList.contains('swiper-slide-active'); 
    const swiperWrapper = swiperSlide.closest('.swiper-wrapper'); 
    const allSlides = Array.from(swiperWrapper.querySelectorAll('.swiper-slide')); 
    const currentIndex = allSlides.indexOf(swiperSlide); 

    const activeSlide = swiperWrapper.querySelector('.swiper-slide-active'); 
    const activeIndex = allSlides.indexOf(activeSlide); 

    if (currentIndex > activeIndex) { 
        teamSwiper.slideNext(); 
    } else if (currentIndex < activeIndex) { 
        teamSwiper.slidePrev(); 
    } 
}); 

// ========================== 
// PARALLAX HERO 
// ========================== 

function initializeParallax() { 
    const parallaxLayers = document.querySelectorAll('.parallax-layer'); 
    if (parallaxLayers.length === 0) return; 

    function updateParallax() { 
        const scrolled = window.pageYOffset; 
        parallaxLayers.forEach((layer, index) => { 
            const speed = (index + 1) * 0.1; 
            const yPos = -(scrolled * speed); 
            layer.style.transform = `translateY(${yPos}px)`; 
        }); 
    } 

    let ticking = false; 
    window.addEventListener('scroll', function() { 
        if (!ticking) { 
            requestAnimationFrame(function() { 
                updateParallax(); 
                ticking = false; 
            }); 
            ticking = true; 
        } 
    }); 

    updateParallax(); 
} 

// ========================== 
// STICKY HEADER 
// ========================== 

function initializeStickyHeader() {
    const header = document.querySelector('header.glass-header');
    const heroSection = document.querySelector('.hero');

    if (!header || !heroSection) return;

    const heroObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) {
                // hero hilang → sticky masuk
                header.classList.remove('sticky-exit');
                header.classList.add('sticky');
            } else {
                // hero muncul → sticky keluar (animasi dulu)
                if (header.classList.contains('sticky')) {
                    header.classList.add('sticky-exit');

                    header.addEventListener('transitionend', () => {
                        header.classList.remove('sticky');
                        header.classList.remove('sticky-exit');
                    }, { once: true });
                }
            }
        });
    }, { threshold: 0 });

    heroObserver.observe(heroSection);
}
