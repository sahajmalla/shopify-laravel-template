export type ApiResponse<T> = {
    success: boolean;
    message: string;
    data?: T;
    errors?: unknown;
    meta?: unknown;
};
