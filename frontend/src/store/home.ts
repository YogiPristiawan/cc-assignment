import env from "@/config/env"

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
  const apiBaseUrl = env.API_BASE_URL ?? ""
  console.log(apiBaseUrl)
  const url = new URL(apiBaseUrl + "/api/transactions")

  const response = await fetch(url, {
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

export type FetchCurrentBalanceResponse = {
  error: boolean
  message: string
  data: {
    current_balance: string
  }
}

export async function fetchCurrentBalance(): Promise<FetchCurrentBalanceResponse> {
  const apiBaseUrl = env.API_BASE_URL ?? ""
  const url = new URL(apiBaseUrl + "/api/current-balance")

  const response = await fetch(url, {
    method: "GET",
    headers: {
      "Content-Type": "application/json"
    }
  });

  const json = await response.json() as { message: string, data: FetchCurrentBalanceResponse["data"] }

  if (!response.ok) {
    return {
      error: true,
      message: json.message,
      data: {
        current_balance: "0"
      }
    }
  }

  return {
    error: false,
    message: json.message,
    data: {
      current_balance: json.data.current_balance
    }
  }
}
