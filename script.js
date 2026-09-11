function openModal(title, company, salary, location, description, jobId) {
    // 1. Get the modal elements
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalCompany').innerText = company;
    document.getElementById('modalSalary').innerText = salary;
    document.getElementById('modalLocation').innerText = location;
    document.getElementById('modalDescription').innerText = description;
    
    // 2. Set the Apply Link dynamically
    document.getElementById('applyLink').href = "apply.php?job_id=" + jobId;
    
    // 3. Show the modal
    document.getElementById('jobModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('jobModal').style.display = 'none';
}

// Close modal if user clicks outside the box
window.onclick = function(event) {
    let modal = document.getElementById('jobModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}