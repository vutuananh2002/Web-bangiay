import type { CustomerGender, OrderStatus } from "./Enum";

interface ListItem {
    value: number,
    label: string,
}

interface PaginationData {
    count: number,
    currentPage: number,
    perPage: number,
    total: number,
    total_pages: number,
}

interface ProductData {
    id: number,
    name: string,
    manufacture: {
        id: number,
        name: string,
    }
    categories: CategoryData[],
    colors: ColorData[],
    types: TypeData[],
    sizes: SizeData[],
    images: Image[],
    stock: number | string,
    price: number,
    total_review: number | string,
    review_details: ReviewData[],
    rating: number | string,
    sold: number,
    slug: string,
    description: string,
    created_at: Date | string,
    updated_at: Date | string,
}

interface ProductDataWithPagination {
    data?: ProductData[],
    pagination?: PaginationData,
}

interface ResponseData {
    success: boolean,
    message: string,
}

interface ProductsDataWithPaginateResponse extends ResponseData {
    links: {},
    products: ProductDataWithPagination,
}

interface ProductDetailResponse extends ResponseData {
    data: ProductData,
}

interface UpdateProductData {
    name: string,
    manufacture_id: string | number,
    categories: any[],
    colors: any[],
    types: any[],
    sizes: any[],
    images: any[],
    stock: number | string,
    price: number,
    description: string,
    slug: string,
}

interface ProductValidationError {
    name?: any[],
    manufacture_id?: any[],
    categories?: any[],
    colors?: any[],
    types?: any[],
    sizes?: any[],
    images?: any[],
    stock?: any[],
    price?: any[],
    description?: any[],
    slug?: any[],
}

interface LoginCredentials {
    email: string,
    password: string,
    remember_me: boolean,
}

interface AccessToken {
    token_type: string,
    token: string,
    expires_at: Date,
}

interface UserData {
    id: number,
    username: string,
    email: string,
    is_verified: string,
    status: string,
    avatar: Image,
    roles: RoleData[],
    admin?: AdminData,
    customer?: CustomerData,
}

interface Image {
    id: number,
    url: string,
    expires_at: Date | null | string,
}

interface AdminData {
    id: number,
    name: string,
    phone_number: string,
    description: string,
    created_at: Date,
    updated_at: Date,
}

interface RoleData {
    role: number,
    name: string,
}

interface UserDataResponse extends ResponseData {
    data: UserData,
}

interface UserAvatarResponse extends ResponseData {
    data: Image,
}

interface AuthResponse extends ResponseData {
    data: {
        access_token: AccessToken,
        user: UserData,
    }
}

interface ManufactureData {
    id: number,
    name: string,
    address: string,
    phone_number: string,
    email: string,
    logo: Image,
    information: string,
    slug: string,
    products_count?: number,
    created_at: Date | string,
    updated_at: Date | string,
}

interface ManufacturesDataResponse extends ResponseData {
    data: ManufactureData[],
}

interface ManufacturesDataWithPagination {
    data?: ManufactureData[],
    pagination?: PaginationData,
}

interface ManufacturesDataWithPaginateResponse extends ResponseData {
    links: {},
    manufactures: ManufacturesDataWithPagination,
}

interface CategoryData {
    id: number,
    name: string,
    slug: string,
    products_count?: number,
    created_at: Date | string,
    updated_at: Date | string,
    isEdit?: boolean,
}

interface CategoriesDataResponse extends ResponseData {
    data: CategoryData[],
}

interface CategoriesDataWithPagination {
    data?: CategoryData[],
    pagination?: PaginationData,
}

interface CategoryDetailResponse extends ResponseData {
    data: CategoryData,
}

interface CategoriesDataWithPaginateResponse extends ResponseData {
    links: {},
    categories: CategoriesDataWithPagination,
}

interface CategoryValidationError {
    name?: any[],
    slug?: any[],
}

interface ColorData {
    id: number,
    color_code: string,
    created_at?: Date | string,
    updated_at?: Date | string,
    products_count?: number,
    isEdit?: boolean,
}

interface ColorsDataResponse extends ResponseData {
    data: ColorData[],
}

interface ColorDetailResponse extends ResponseData {
    data: ColorData,
}

interface SizeData {
    id: number,
    name: string,
    products_count?: number,
    created_at?: Date | string,
    updated_at?: Date | string,
    isEdit?: boolean,
}

interface SizesDataWithPagination {
    data?: SizeData[],
    pagination?: PaginationData[],
}

interface SizesDataWithPaginateResponse extends ResponseData {
    links: PaginationLink,
    sizes: SizesDataWithPagination,
}

interface SizesDataResponse extends ResponseData {
    data: SizeData[],
}

interface SizeDetailResponse extends ResponseData {
    data: SizeData,
}

interface TypeData {
    id: number,
    type: string,
    slug?: string,
    created_at?: Date | string,
    updated_at?: Date | string,
    isEdit?: boolean,
}

interface TypesDataResponse extends ResponseData {
    data: TypeData[],
}

interface TypeDetailResponse extends ResponseData {
    data: TypeData,
}

interface ImageDetailResponse extends ResponseData {
    data: Image,
}

interface UpdateManufactureData {
    name: string,
    address: string,
    phone_number: string,
    email: string,
    information: string,
    slug: string,
    logo?: Image | any,
}

interface ManufactureDetailResponse extends ResponseData {
    data: ManufactureData,
}

interface ManufactureValidationError {
    name: any[],
    address: any[],
    phone_number: any[],
    email: any[],
    information: any[],
    slug: any[],
}

interface ReviewData {
    id: number,
    comment: string,
    star: number,
    created_at: Date | string,
    updated_at: Date | string,
    customer: {
        avatar: string,
        name: string,
    }
}

interface HomeData {
    new_arrivals: {
        title: string,
        items: ProductData[],
    },
    best_sellers: {
        title: string,
        items: ProductData[],
    },
    top_rating: {
        title: string,
        items: ProductData[],
    },
    top_reviews: {
        title: string,
        items: ReviewData[],
    },
}

interface HomeDataResponse extends ResponseData {
    data: HomeData
}

interface CartData {
    id: number,
    cart_key: string,
    products: CartItem[],
    created_at: Date | string,
    updated_at: Date | string,
}

interface CartItem {
    id: number,
    name: string,
    price: number,
    quantity: number,
    color: ColorData,
    size: SizeData,
    type: TypeData,
    image?: string,
    slug?: string,
}

interface CustomerData {
    id: number,
    full_name: string,
    gender: string,
    phone_number: string,
    address: string,
    created_at: string,
    updated_at: string,
}

interface CustomerDataResponse {
    data: CustomerData,
}

interface PaginationLink {
    first: string,
    last: string,
    next: string,
    prev: string,
}

interface ColorsDataWithPagination {
    data?: ColorData[],
    pagination?: PaginationData[],
}

interface ColorsDataWithPaginationResponse extends ResponseData {
    links: {},
    colors: ColorsDataWithPagination,
}

interface PriceData {
    name: string,
    products_count: number,
}

interface PricesDataResponse extends ResponseData {
    data: PriceData[],
}

interface RateData {
    rate: number,
    products_count?: number,
}

interface RateDataResponse extends ResponseData {
    data: RateData[],
}

interface WardData {
    name: string,
    code: number,
    district_code: number,
}

interface DistrictData {
    name: string,
    code: number,
    province_code: number,
    wards: WardData[],
}

interface ProvinceData {
    code: number,
    districts: DistrictData[],
    name: string,
}

interface BannerData {
    id: number,
    title: string,
    url: string,
    description: string,
    created_at: Date | null,
    updated_at: Date | null,
}

interface BannersDataResponse extends ResponseData {
    data: BannerData[],
}

interface BannerDetailResponse extends ResponseData {
    data: BannerData,
}

interface UpdateBannerData {
    title: string,
    image: Image | any,
    description: string,
}

interface ReviewDetailResponse extends ResponseData {
    data: ReviewData,
}

interface CartDetailResponse extends ResponseData {
    data: CartData,
}

interface RegisterCredentials {
    avatar: any,
    username: string,
    email: string,
    password: string,
    password_confirmation: string,
    first_name: string,
    last_name: string,
    gender: CustomerGender,
    province?: any,
    district?: any,
    ward?: any,
    address: string,
    phone_number: string,
}

interface RegisterCredentialsValidationError {
    username: any[],
    email: any[],
    password: any[],
    password_confirmation: any[],
    first_name: any[],
    last_name: any[],
    gender: any[],
    address: any[],
    phone_number: any[],
}

interface AccessTokenResponse extends ResponseData {
    data: {
        access_token: AccessToken,
    },
}

interface OrderData {
    id: number,
    customer?: CustomerData,
    products: CartItem[],
    receiver_name: string,
    receiver_address: string,
    receiver_phone_number: string,
    total_price: string,
    status: string,
    transaction_id: string,
    created_at: Date | string,
    updated_at: Date | string,
}

interface OrderDetailResponse extends ResponseData {
    data: OrderData,
}

interface OrderDataWithPagination {
    data: OrderData[],
    pagination?: PaginationData,
}

interface OrdersDataWithPaginateResponse extends ResponseData {
    orders: OrderDataWithPagination,
    links: PaginationLink,
}

interface UsersDataWithPagination {
    data: UserData[],
    pagination?: PaginationData,
}

interface UsersDataWithPaginateResponse extends ResponseData {
    users: UsersDataWithPagination,
    links: PaginationLink,
}

interface CustomersDataWithPagination {
    data: CustomerData[],
    pagination?: PaginationData,
}

interface CustomersDataWithPaginateResponse extends ResponseData {
    customers: CustomersDataWithPagination,
    links: PaginationLink,
}

interface ReportData {
    total_product: number,
    total_revenue: number,
    total_customer: number,
    total_order: number,
    top_rating_chart: any,
    sales_by_month_chart: any,
    revenue_by_month_chart: any,
}

interface ReportDataResponse extends ResponseData {
    data: ReportData,
}

export type {
    ResponseData, ProductData, ProductsDataWithPaginateResponse, LoginCredentials, AccessToken, UserData, UserDataResponse, UserAvatarResponse, AuthResponse, PaginationData, ProductDataWithPagination, ManufactureData,
    ManufacturesDataResponse, CategoryData, CategoriesDataResponse, ColorData, ColorsDataResponse, SizeData, SizesDataResponse,
    TypeData, TypesDataResponse, ListItem, ProductDetailResponse, TypeDetailResponse, UpdateProductData, ImageDetailResponse, ProductValidationError,
    ColorDetailResponse, SizeDetailResponse, CategoriesDataWithPagination, CategoriesDataWithPaginateResponse, CategoryDetailResponse,
    CategoryValidationError, ManufacturesDataWithPagination, ManufacturesDataWithPaginateResponse, UpdateManufactureData,
    ManufactureDetailResponse, ManufactureValidationError, HomeData, HomeDataResponse, ReviewData, CartItem, CartData, PaginationLink,
    ColorsDataWithPagination, ColorsDataWithPaginationResponse, PriceData, PricesDataResponse, SizesDataWithPaginateResponse, RateDataResponse, RateData,
    ProvinceData, DistrictData, WardData, CustomerDataResponse, BannersDataResponse, BannerDetailResponse, BannerData, UpdateBannerData,
    ReviewDetailResponse, CartDetailResponse, RegisterCredentials, RegisterCredentialsValidationError, AccessTokenResponse, OrderData, OrderDetailResponse,
    OrdersDataWithPaginateResponse, UsersDataWithPaginateResponse, CustomersDataWithPaginateResponse, CustomerData, ReportDataResponse, ReportData
};