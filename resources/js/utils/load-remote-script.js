export default function LoadRemoteScript(src) {
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.async = true;
        script.src = src;

        script.onload = resolve
        script.onerror = reject

        document.body.appendChild(script);
    })
}
