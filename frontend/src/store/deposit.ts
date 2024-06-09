import env from "@/config/env"

export type CreateDepositArgs = {
  amount: string
}

export type CreateDepositResopnse = {
  error: boolean
  message: string
}

export const createDeposit = async (args: CreateDepositArgs) => {
  const payload = {
    transaction: {
      amount: args.amount
    }
  }

  const apiBaseUrl = env.API_BASE_URL ?? ""
  const url = new URL(apiBaseUrl + "/api/deposit")

  const response = await fetch(url, {
    method: "POST",
    body: JSON.stringify(payload),
    headers: {
      "Content-Type": "application/json"
    }
  })

  let json: CreateDepositResopnse | null = null
  try {
    json = (await response.json()) as CreateDepositResopnse
  } catch (err) {
    console.log(err)
  }

  if (!response.ok) {
    return {
      error: true,
      message: json ? json.message : "something went wrong"
    }
  }

  return {
    error: false,
    message: json ? json.message : "success"
  }
}
