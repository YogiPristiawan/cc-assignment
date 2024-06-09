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

  const response = await fetch("http://localhost:8000/api/deposit", {
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
