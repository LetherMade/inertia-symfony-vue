interface ILink {
    url: string|null
    label: string
    active: boolean
}

interface IPaginator<T> {
    data: T[]
    links: ILink[]
}