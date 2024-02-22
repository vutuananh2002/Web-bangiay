const numberFormat = (number: number): string => {
    return Intl.NumberFormat('en', { notation: 'compact' }).format(number);
}

const currencyFormat = (value: any) => (`${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ','));

const currencyParser = (value: string): string => (value.replace(/VND\s?|(,*)/g, ''));

export { numberFormat, currencyFormat, currencyParser };