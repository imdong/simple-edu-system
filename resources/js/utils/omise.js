import LoadRemoteScript from "./load-remote-script.js";

const PUBLIC_KEY = import.meta.env.VITE_OMISE_PUBLIC_KEY


export default function CreateToken(config) {
    return new Promise((onCreateTokenSuccess, reject) => {
        config = Object.assign(config, {onCreateTokenSuccess})
        // 是否已加载
        if (typeof OmiseCard === 'undefined') {
            LoadRemoteScript('https://cdn.omise.co/omise.js').then(() => {
                // 配置
                OmiseCard.configure({
                    publicKey: PUBLIC_KEY
                });

                OmiseCard.open(config);
            })
        } else {
            OmiseCard.open(config);
        }
    })
}
