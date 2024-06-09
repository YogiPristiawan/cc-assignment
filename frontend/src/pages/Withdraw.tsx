import { Input } from "@/components/ui/input"
import { Card, CardHeader, CardContent } from "@/components/ui/card"
import { Label } from "@radix-ui/react-label"
import { Button } from "@/components/ui/button"
import { Separator } from "@/components/ui/separator"
import { CreateDepositArgs, createDeposit } from "@/store/deposit"

export default function Withdraw() {

  const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault()

    // createDeposit({
    //   amount: e
    // })
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
          <Input id="amount" type="number" name="amount" />

          <Button type="submit" variant="default" className="mt-4">Simpan</Button>
        </form>
      </CardContent>
    </Card>
  )
}

