import { createAppKit } from '@reown/appkit';
import { EthersAdapter } from '@reown/appkit-adapter-ethers';
import {
    createSIWEConfig,
    formatMessage
} from '@reown/appkit-siwe';
import { mainnet } from '@reown/appkit/networks';

// Initialize SIWE with the provided configuration
export const createSiwe = () => {
    window.initSiwe = (siweConfig, projectId) => {
        if (!siweConfig || !projectId) {
            console.error('Missing required SIWE configuration');
            return;
        }
        // Create the AppKit instance
        return createAppKit({
            adapters: [new EthersAdapter()],
            projectId,
            networks: [mainnet],
            defaultNetwork: mainnet,
            themeMode: siweConfig.theme ?? 'light',
            siweConfig: createSIWEConfig({
                getMessageParams: async () => ({
                    domain: siweConfig.domain,
                    uri: siweConfig.uri,
                    chains: [siweConfig.chainId],
                    statement: siweConfig.statement,
                    nonce: siweConfig.nonce,
                    version: siweConfig.version || '1'
                }),

                createMessage: ({ address, ...args }) => {
                    console.log(args, address);
                    return formatMessage(args, address)
                },
                // Use the provided verifyMessage function or default to a simple one
                verifyMessage: siweConfig.verifyMessage || (async () => true),
                // Basic nonce management
                getNonce: siweConfig.getNonce || (async () => siweConfig.nonce),
                getSession: async () => null, // We'll handle sessions on the server
                signOut: async () => true
            })
        });
    };
}
