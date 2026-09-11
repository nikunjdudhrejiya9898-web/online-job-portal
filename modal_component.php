<div id="jobModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2 id="modalTitle">Job Title</h2>
        <h4 id="modalCompany" style="color:#777;">Company Name</h4>
        <hr>
        <p><strong>Location:</strong> <span id="modalLocation"></span></p>
        <p><strong>Salary:</strong> <span id="modalSalary"></span></p>
        <p><strong>Description:</strong></p>
        <p id="modalDescription" style="background:#f9f9f9; padding:10px; border-radius:5px; max-height: 200px; overflow-y: auto;"></p>
        
        <div style="text-align: center;">
            <a id="applyLink" href="#" class="btn-apply">Apply Now</a>
        </div>
    </div>
</div>