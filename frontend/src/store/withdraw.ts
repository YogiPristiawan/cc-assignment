export type CreateWithdrawArgs = {
  amount: string
}

export type CreateWithdrawResopnse = {
  error: boolean
  message: string
}

export const createWithdraw = async (args: CreateWithdrawArgs) => {
  const payload = {
    transaction: {
      amount: args.amount
    }
  }

  const response = await fetch("http://localhost:8000/api/withdraw", {
    method: "POST",
    body: JSON.stringify(payload),
    headers: {
      "Content-Type": "application/json"
    }
  })

  let json: CreateWithdrawResopnse | null = null
  try {
    json = (await response.json()) as CreateWithdrawResopnse
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
