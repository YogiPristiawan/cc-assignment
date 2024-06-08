export type FetchTransactionResponse = {
  error: boolean,
  message: string,
  data: {
    order_id: string
    amount: string
    type: string
    status: string
    created_at: string
  }[]
}

export async function fetchTransactions(): Promise<FetchTransactionResponse> {
  const response = await fetch("http://localhost:8000/api/transactions", {
    method: "GET",
    cache: "no-store"
  })

  const json = await response.json() as { message: string, data: FetchTransactionResponse["data"] }

  if (!response.ok) {
    return {
      error: true,
      message: json.message,
      data: []
    }
  }

  return {
    error: false,
    message: json.message,
    data: json.data
  }
}
