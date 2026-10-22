import { MindARThree } from 'mind-ar';

const start = document.getElementById('startAr');
const select = document.getElementById('posterSelect');
const msg = document.getElementById('arMessage');
const card = document.getElementById('arCard');
const status = document.getElementById('trackingStatus');
const help = document.getElementById('arHelp');
const camera = document.getElementById('arCamera');
let mindar;

function escapeHtml(value) {
    return String(value || '').replace(/[&<>"']/g, character => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
    }[character]));
}

function escapeAttr(value) {
    return escapeHtml(value);
}

function renderVideo(hotspot) {
    const attraction = hotspot?.attraction;
    const video = hotspot?.videoUrl || attraction?.youtubeUrl;
    if (!hotspot || !video) {
        card.innerHTML = '<strong>No video is configured for this poster.</strong>';
        card.classList.remove('d-none');
        return;
    }

    const youtubeId = video?.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([A-Za-z0-9_-]{6,})/)?.[1];
    const videoMarkup = youtubeId
        ? `<div class="ratio ratio-16x9 mb-2"><iframe src="https://www.youtube.com/embed/${youtubeId}?autoplay=1&playsinline=1" title="Attraction video" allow="autoplay; fullscreen" allowfullscreen></iframe></div>`
        : video
            ? `<video class="w-100 mb-2" controls autoplay playsinline src="${escapeAttr(video)}"></video>`
            : '<p class="small mb-0">This poster has no video URL yet.</p>';

    const title = attraction?.name || hotspot.label || 'Poster video';
    const description = attraction?.shortDescription || attraction?.description || '';
    card.innerHTML = `<div class="d-flex justify-content-between"><strong>${escapeHtml(title)}</strong><button class="btn btn-sm text-light" id="closeVideo">×</button></div>${videoMarkup}<p class="small mt-2 mb-0">${escapeHtml(description)}</p>`;
    card.classList.remove('d-none');
    document.getElementById('closeVideo')?.addEventListener('click', () => {
        card.classList.add('d-none');
        card.querySelector('video')?.pause();
    });
}

start.addEventListener('click', async () => {
    const id = select.value;
    const targetFile = select.selectedOptions[0]?.dataset.target;
    if (!id || !targetFile) {
        help.textContent = 'Please ask an administrator to upload a .mind target first.';
        return;
    }

    try {
        if (!window.isSecureContext || !navigator.mediaDevices?.getUserMedia) {
            throw new Error('Camera access requires HTTPS or http://localhost.');
        }
        start.disabled = true;
        help.textContent = 'Loading poster target and requesting camera…';
        const response = await fetch(`api/ar.php?poster_id=${encodeURIComponent(id)}`);
        const data = await response.json();
        if (!response.ok) throw new Error(data.error || 'Unable to load poster');

        if (!data.poster.hotspots.length) {
            throw new Error('This poster has no hotspots. Add a hotspot in Admin → AR Posters first.');
        }
        const target = data.poster.hotspots.find(hotspot => hotspot.videoUrl || hotspot.attraction?.youtubeUrl);
        if (!target) throw new Error('This poster has hotspots, but no hotspot has a video URL yet.');

        mindar = new MindARThree({
            container: camera,
            imageTargetSrc: targetFile
        });
        const anchor = mindar.addAnchor(0);
        anchor.onTargetFound = () => {
            status.textContent = 'IMAGE FOUND';
            status.className = 'badge text-bg-success';
            help.textContent = 'Image found. Playing video.';
            renderVideo(target);
        };
        anchor.onTargetLost = () => {
            status.textContent = 'SEARCHING';
            status.className = 'badge text-bg-warning';
            help.textContent = 'Point the camera at the poster again.';
        };

        await mindar.start();
        const { renderer, scene, camera: arCamera } = mindar;
        renderer.setAnimationLoop(() => renderer.render(scene, arCamera));
        msg.classList.add('d-none');
        status.textContent = 'SEARCHING';
        status.className = 'badge text-bg-warning';
        help.textContent = 'Point the camera at the poster to play its video.';
    } catch (error) {
        const errorMessage = error instanceof Error ? error.message : String(error || '');
        help.textContent = errorMessage === 'NotAllowedError'
            ? 'Camera access was denied. Please allow camera permission.'
            : errorMessage || 'Unable to start the camera. Check browser permissions and try again.';
        status.textContent = 'ERROR';
        status.className = 'badge text-bg-danger';
        start.disabled = false;
    }
});
