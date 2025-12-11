@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Profile Pictures</h1>
    <a href="/user/{{$user->id}}">&larr; Back to Profile</a>

    <div class="profile-picture-upload">
        <h2>Upload Profile Picture</h2>
        <p>Upload a new profile picture. This will replace your current profile picture.</p>
        <form action="/user/{{$user->id}}/profile-picture/upload" method="POST" enctype="multipart/form-data" id="upload-form">
            @csrf
            <div class="form-group">
                <label for="profile_picture">Choose Image:</label>
                <input type="file" name="profile_picture" id="profile_picture" accept="image/jpeg,image/jpg,image/png,image/gif" required>
                <small>Supported formats: JPG, JPEG, PNG, GIF. Image will be resized to multiple sizes automatically (360px, 160px, 90px, 32px).</small>
            </div>
            @error('profile_picture')
                <div class="error">{{ $message }}</div>
            @enderror
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>

    <div class="current-profile-pictures">
        <h2>Current Profile Picture</h2>
        <p>You can only have one profile picture at a time. Uploading a new picture will replace the current one.</p>
        <div id="picture-gallery" class="picture-grid">
            <p>Loading...</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif
</div>

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.profile-picture-upload {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input[type="file"] {
    display: block;
    margin-bottom: 5px;
}

.form-group small {
    color: #666;
    font-size: 0.9em;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-danger {
    background: #dc3545;
    color: white;
    padding: 5px 10px;
    font-size: 14px;
}

.btn-danger:hover {
    background: #c82333;
}

.btn-set-primary {
    background: #28a745;
    color: white;
    padding: 5px 10px;
    font-size: 14px;
}

.btn-set-primary:hover {
    background: #218838;
}

.picture-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.picture-item {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 10px;
    background: white;
    text-align: center;
}

.picture-item img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
    margin-bottom: 10px;
}

.picture-item.current-pfp {
    border: 3px solid #28a745;
    box-shadow: 0 0 10px rgba(40, 167, 69, 0.3);
}

.picture-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-top: 10px;
}

.alert {
    padding: 15px;
    border-radius: 4px;
    margin: 15px 0;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.error {
    color: #dc3545;
    font-size: 0.9em;
    margin-top: 5px;
}

.badge {
    display: inline-block;
    padding: 5px 10px;
    background: #28a745;
    color: white;
    border-radius: 4px;
    font-size: 0.85em;
    margin-bottom: 10px;
}
</style>

<script type="module">
    // Fetch and display user's profile pictures
    async function loadProfilePictures() {
        try {
            const response = await fetch('/user/{{$user->id}}/profile-pictures/json');
            const pictures = await response.json();
            
            const gallery = document.getElementById('picture-gallery');
            
            if (!pictures || pictures.length === 0 || pictures === null) {
                gallery.innerHTML = '<p>No profile pictures uploaded yet.</p>';
                return;
            }

            gallery.innerHTML = '';
            
            // Show only the most recent (last) picture since we only support single profile picture
            const picture = pictures[pictures.length - 1];
            
            const pictureDiv = document.createElement('div');
            pictureDiv.className = 'picture-item current-pfp';
            
            pictureDiv.innerHTML = `
                <span class="badge">Your Profile Picture</span>
                <img src="/${picture.src}" alt="Profile Picture">
                <div class="picture-actions">
                    <button class="btn btn-danger" onclick="deletePicture(${picture.picture_id})">Delete</button>
                </div>
            `;
            
            gallery.appendChild(pictureDiv);
        } catch (error) {
            console.error('Error loading profile pictures:', error);
            document.getElementById('picture-gallery').innerHTML = '<p>Error loading pictures.</p>';
        }
    }

    // Delete profile picture
    window.deletePicture = async function(pfpId) {
        if (!confirm('Are you sure you want to delete this profile picture? This action cannot be undone.')) {
            return;
        }

        try {
            const response = await fetch(`/user/{{$user->id}}/profile-picture/${pfpId}/delete`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();
            
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to delete picture: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error deleting picture:', error);
            alert('An error occurred while deleting the picture');
        }
    };

    // Load pictures on page load
    document.addEventListener('DOMContentLoaded', loadProfilePictures);
</script>
@endsection
