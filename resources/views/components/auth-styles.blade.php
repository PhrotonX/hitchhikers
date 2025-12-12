{{-- Auth Page Styling Component - Include in @push('head') --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    :root {
        --primary: #1E3A8A;
        --primary-light: #3B5FCF;
        --text-dark: #1F2937;
        --text-light: #6B7280;
        --border: #E5E7EB;
        --card-bg: white;
        --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --error: #EF4444;
        --success: #10B981;
    }

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        font-family: 'Poppins', sans-serif;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .auth-container {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px 20px;
    }

    .auth-card {
        background-color: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 40px;
        box-shadow: var(--shadow);
        max-width: 500px;
        width: 100%;
        border: 1px solid var(--border);
    }

    .auth-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .auth-header h1 {
        font-size: 28px;
        color: var(--primary);
        font-weight: 700;
        margin-bottom: 8px;
    }

    .auth-header p {
        color: var(--text-light);
        font-size: 14px;
    }

    .input-group {
        margin-bottom: 20px;
    }

    .input-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-dark);
        font-size: 14px;
        font-weight: 500;
    }

    .input-group input,
    .input-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border);
        background-color: white;
        border-radius: 8px;
        color: var(--text-dark);
        font-size: 16px;
        transition: all 0.2s ease;
        font-family: inherit;
        box-sizing: border-box;
    }

    .input-group input:focus,
    .input-group select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.15);
    }

    .auth-btn {
        width: 100%;
        padding: 14px;
        background-color: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .auth-btn:hover {
        background-color: var(--primary-light);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .auth-btn-secondary {
        background-color: var(--text-light);
    }

    .auth-btn-secondary:hover {
        background-color: var(--text-dark);
    }

    .error-message {
        background-color: #FEE2E2;
        border: 1px solid var(--error);
        color: var(--error);
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .success-message {
        background-color: #D1FAE5;
        border: 1px solid var(--success);
        color: var(--success);
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .auth-footer {
        text-align: center;
        margin-top: 20px;
        color: var(--text-light);
        font-size: 14px;
    }

    .auth-footer a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
    }

    .auth-footer a:hover {
        text-decoration: underline;
    }
</style>
