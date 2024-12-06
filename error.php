
// Fetch existing learners
$learners = [];
$result = $conn->query("SELECT * FROM learners WHERE grade_level = '7'");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $learners[] = $row;
    }
}

$conn->close();
?>






<!-- Action buttons (Accept and Reject) -->
<td class="text-center">
    <button class="btn btn-success btn-sm accept-btn" 
        data-id="<?php echo $learner['id']; ?>" 
        onclick="confirmAccept(<?php echo $learner['id']; ?>)">
        <i class="fas fa-check"></i> Accept
    </button>
    <button class="btn btn-danger btn-sm reject-btn" 
        data-id="<?php echo $learner['id']; ?>" 
        onclick="confirmReject(<?php echo $learner['id']; ?>)">
        <i class="fas fa-times"></i> Reject
    </button>
</td>