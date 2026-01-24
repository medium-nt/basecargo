$(document).ready(function() {
    $('.select2').select2({
        width: '100%',
    });
});

function calculateGrossWeightPerPlace() {
    const grossWeightTotal = parseFloat(document.getElementById('gross_weight_total').value) || 0;
    const placesCount = parseInt(document.getElementById('places_count').value) || 0;

    if (placesCount > 0 && grossWeightTotal > 0) {
        const result = grossWeightTotal / placesCount;
        document.getElementById('gross_weight_per_place').value = result.toFixed(2);
    } else {
        document.getElementById('gross_weight_per_place').value = '';
    }

    calculateTareWeightPerBox();
}

function calculateVolumePerItem() {
    const volumeTotal = parseFloat(document.getElementById('volume_total').value) || 0;
    const placesCount = parseInt(document.getElementById('places_count').value) || 0;

    if (placesCount > 0 && volumeTotal > 0) {
        const result = volumeTotal / placesCount;
        document.getElementById('volume_per_item').value = result.toFixed(2);
    } else {
        document.getElementById('volume_per_item').value = '';
    }
}

function calculateTareWeightTotal() {
    const grossWeightTotal = parseFloat(document.getElementById('gross_weight_total').value) || 0;
    const netWeightTotal = parseFloat(document.getElementById('net_weight_total').value) || 0;

    if (grossWeightTotal > 0 && netWeightTotal > 0) {
        const result = grossWeightTotal - netWeightTotal;
        document.getElementById('tare_weight_total').value = result.toFixed(2);
    } else {
        document.getElementById('tare_weight_total').value = '';
    }
}

function calculateNetWeightPerBox() {
    const netWeightTotal = parseFloat(document.getElementById('net_weight_total').value) || 0;
    const placesCount = parseInt(document.getElementById('places_count').value) || 0;

    if (placesCount > 0 && netWeightTotal > 0) {
        const result = netWeightTotal / placesCount;
        document.getElementById('net_weight_per_box').value = result.toFixed(2);
    } else {
        document.getElementById('net_weight_per_box').value = '';
    }

    calculateTareWeightPerBox();
}

function calculateTareWeightPerBox() {
    const grossWeightPerPlace = parseFloat(document.getElementById('gross_weight_per_place').value) || 0;
    const netWeightPerBox = parseFloat(document.getElementById('net_weight_per_box').value) || 0;

    if (grossWeightPerPlace > 0 && netWeightPerBox > 0) {
        const result = grossWeightPerPlace - netWeightPerBox;
        document.getElementById('tare_weight_per_box').value = result.toFixed(2);
    } else {
        document.getElementById('tare_weight_per_box').value = '';
    }
}

document.getElementById('gross_weight_total').addEventListener('input', calculateGrossWeightPerPlace);
document.getElementById('places_count').addEventListener('input', calculateGrossWeightPerPlace);

document.getElementById('volume_total').addEventListener('input', calculateVolumePerItem);
document.getElementById('places_count').addEventListener('input', calculateVolumePerItem);

document.getElementById('gross_weight_total').addEventListener('input', calculateTareWeightTotal);
document.getElementById('net_weight_total').addEventListener('input', calculateTareWeightTotal);

document.getElementById('net_weight_total').addEventListener('input', calculateNetWeightPerBox);
document.getElementById('places_count').addEventListener('input', calculateNetWeightPerBox);

// ========== Автоматический расчёт вычисляемых полей ==========

function calculateEstimatedValue() {
    const netWeight = parseFloat(document.getElementById('net_weight_total').value) || 0;
    const its = parseFloat(document.getElementById('ITS').value) || 0;
    const result = netWeight * its;
    document.getElementById('estimated_value_cargo_ITS').value = result.toFixed(2);
    calculateTotalPayment();
}

function calculateTotalPayment() {
    const A = parseFloat(document.getElementById('estimated_value_cargo_ITS').value) || 0;
    const duty = parseFloat(document.getElementById('duty').value) / 100 || 0;
    const vat = parseFloat(document.getElementById('VAT').value) / 100 || 0;
    const result = (A * duty) + (A + (A * duty)) * vat;
    document.getElementById('total_payment').value = result.toFixed(2);
    calculateImporterServices();
}

function calculateImporterServices() {
    const volume = parseFloat(document.getElementById('volume_total').value) || 0;
    const volumeWeight = parseFloat(document.getElementById('volume_weight').value) || 0;
    const service = parseFloat(document.getElementById('customs_clearance_service').value) || 0;

    if (volumeWeight > 0) {
        const result = (volume / volumeWeight) * service;
        document.getElementById('importer_services').value = result.toFixed(2);
    } else {
        document.getElementById('importer_services').value = '0.00';
    }
    calculateDeliveryToUssuriysk();
}

function calculateDeliveryToUssuriysk() {
    const costTruck = parseFloat(document.getElementById('cost_truck').value) || 0;
    const volumeWeight = parseFloat(document.getElementById('volume_weight').value) || 0;
    const volume = parseFloat(document.getElementById('volume_total').value) || 0;

    if (volumeWeight > 0) {
        const result = (costTruck / volumeWeight) * volume;
        document.getElementById('delivery_to_Ussuriysk').value = result.toFixed(2);
    } else {
        document.getElementById('delivery_to_Ussuriysk').value = '0.00';
    }
    calculateRevenue();
}

function calculateRevenue() {
    const revenuePerKg = parseFloat(document.getElementById('revenue_per_kg').value) || 0;
    const grossWeight = parseFloat(document.getElementById('gross_weight_total').value) || 0;
    const result = revenuePerKg * grossWeight;
    document.getElementById('revenue').value = result.toFixed(2);
    calculateTotal();
}

function calculateTotal() {
    const B = parseFloat(document.getElementById('total_payment').value) || 0;
    const C = parseFloat(document.getElementById('importer_services').value) || 0;
    const D = parseFloat(document.getElementById('delivery_to_Ussuriysk').value) || 0;
    const E = parseFloat(document.getElementById('revenue').value) || 0;
    const dollarRate = parseFloat(document.getElementById('dollar_rate').value) || 0;
    const result = ((B + C) * dollarRate) + D + E;
    document.getElementById('total').value = result.toFixed(2);
    calculateTotalPerKg();
}

function calculateTotalPerKg() {
    const G = parseFloat(document.getElementById('total').value) || 0;
    const grossWeight = parseFloat(document.getElementById('gross_weight_total').value) || 0;
    const yuanRate = parseFloat(document.getElementById('yuan_rate').value) || 0;

    if (grossWeight > 0 && yuanRate > 0) {
        const result = G / grossWeight / yuanRate;
        document.getElementById('total_per_kg').value = result.toFixed(2);
    } else {
        document.getElementById('total_per_kg').value = '0.00';
    }
}

// Event listeners для вычисляемых полей
document.getElementById('net_weight_total').addEventListener('input', calculateEstimatedValue);
document.getElementById('ITS').addEventListener('input', calculateEstimatedValue);
document.getElementById('duty').addEventListener('input', calculateEstimatedValue);
document.getElementById('VAT').addEventListener('input', calculateEstimatedValue);

document.getElementById('volume_total').addEventListener('input', calculateEstimatedValue);
document.getElementById('volume_weight').addEventListener('input', calculateEstimatedValue);
document.getElementById('customs_clearance_service').addEventListener('input', calculateEstimatedValue);

document.getElementById('cost_truck').addEventListener('input', calculateEstimatedValue);

document.getElementById('revenue_per_kg').addEventListener('input', calculateEstimatedValue);
document.getElementById('gross_weight_total').addEventListener('input', calculateEstimatedValue);

document.getElementById('dollar_rate').addEventListener('input', calculateEstimatedValue);
document.getElementById('yuan_rate').addEventListener('input', calculateEstimatedValue);

// Вычислить readonly поля при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    calculateEstimatedValue();
});
