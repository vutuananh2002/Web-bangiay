enum CustomerGender {
    female = 0,
    male = 1,
}

enum StatusCode {
    Unauthorized = 401,
    Forbidden = 403,
}

enum PaymentMethod {
    Cash = 1,
    Banking = 2,
}

enum OrderStatus {
    Pending = 0,
    Delivering = 1,
    Delivered = 2,
    Received = 3,
    Cancelled = 4,
}

export { CustomerGender, StatusCode, PaymentMethod, OrderStatus };