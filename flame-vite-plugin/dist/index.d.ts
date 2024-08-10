import { UserConfig } from "vite";
import { OutputOptions, OutputBundle } from "rollup";
export type PluginConfig = {
    input: string | Array<string> | Record<string, string>;
    publicPath?: string;
};
declare const _default: ({ input, publicPath }: PluginConfig) => {
    name: string;
    config: (config: UserConfig, env: {
        command: string;
        mode: string;
    }) => {
        build: {
            manifest: string | boolean;
            outDir: string;
            emptyOutDir: boolean;
            rollupOptions: {
                input: string[];
            };
        };
    };
    writeBundle: (options: OutputOptions, bundle: OutputBundle) => Promise<void>;
};
export default _default;
