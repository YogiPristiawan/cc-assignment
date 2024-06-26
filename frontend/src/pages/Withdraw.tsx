import { Input } from "@/components/ui/input"
import { Card, CardHeader, CardContent } from "@/components/ui/card"
import { Label } from "@radix-ui/react-label"
import { Button } from "@/components/ui/button"
import { Separator } from "@/components/ui/separator"
import { createWithdraw, CreateWithdrawArgs } from "@/store/withdraw"
import { useState } from "react"
import { useNavigate } from "react-router-dom"

export default function Withdraw() {
  const [input, setInput] = useState<CreateWithdrawArgs>({ amount: "" })
  const navigate = useNavigate()

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault()
    const response = await createWithdraw(input)
    alert(response.message)
    return navigate("/")
  }

  return (
    <Card className="max-w-[800px] min-w-[500px] border">
      <CardHeader className="text-center">
        <h3 className="text-xl font-bold">Masukkan Nominal Withdraw</h3>
      </CardHeader>
      <Separator />
      <CardContent className="pt-6">
        <form onSubmit={(e) => handleSubmit(e)}>
          <Label htmlFor="amount">Nominal</Label>
          <Input id="amount" step=".01" type="number" name="amount" onChange={(e) => {
            setInput((prev) => {
              return {
                ...prev,
                amount: Number(e.target.value).toFixed(2).toString()
              }
            })
          }} />

          <Button type="submit" variant="default" className="mt-4">Simpan</Button>
        </form>
      </CardContent>
    </Card>
  )
}

